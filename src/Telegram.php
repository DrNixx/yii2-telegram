<?php
namespace onix\telegram;

use onix\telegram\commands\CallbackQueryHandler;
use onix\telegram\commands\Command;
use onix\telegram\commands\system\CallbackqueryCommand;
use onix\telegram\entities\ServerResponse;
use onix\telegram\entities\Update;
use onix\telegram\exceptions\TelegramException;
use yii\base\Component;
use yii\base\Exception as BaseException;
use yii\base\InvalidConfigException;
use yii\helpers\Json;

/**
 * Class Telegram
 *
 * @property-read string $apiKey
 * @property-read string $botUsername
 * @property-read Request $request
 */
class Telegram extends Component
{
    /**
     * Telegram API key
     *
     * @var string
     */
    public string $api_key = '';

    /**
     * Telegram Bot username
     *
     * @var string
     */
    public string $bot_username = '';

    /**
     * Custom commands namespaces
     *
     * @var array
     */
    public array $commandsNamespaces = [];

    /**
     * Telegram Bot id
     *
     * @var string
     */
    protected string $bot_id = '';

    /**
     * Custom commands objects
     *
     * @var Command[]
     */
    protected array $commands_objects = [];

    /**
     * Current Update object
     *
     * @var Update|null
     */
    protected ?Update $update = null;

    /**
     * Upload path
     *
     * @var string
     */
    public string $uploadPath = '@app/runtime/telegram/upload';

    /**
     * Download path
     *
     * @var string
     */
    public string $downloadPath = '@app/runtime/telegram/download';

    /**
     * Commands config
     *
     * @var array
     */
    protected array $commands_config = [];

    /**
     * Admins list
     *
     * @var array
     */
    public array $admins = [];

    /**
     * ServerResponse of the last Command execution
     *
     * @var ServerResponse|null
     */
    protected ?ServerResponse $last_command_response = null;

    /**
     * Check if runCommands() is running in this session
     *
     * @var bool
     */
    protected bool $run_commands = false;

    /**
     * Last update ID
     * Only used when running getUpdates without a database
     *
     * @var integer|null
     */
    protected ?int $last_update_id = null;

    /**
     * The command to be executed when there's a new message update and nothing more suitable is found
     */
    const GENERIC_MESSAGE_COMMAND = 'genericmessage';

    /**
     * The command to be executed by default (when no other relevant commands are applicable)
     */
    const GENERIC_COMMAND = 'generic';

    /**
     * Update filter
     * Filter updates
     *
     * @var callback
     */
    protected $update_filter;

    /**
     * @var Request
     */
    private Request $requestInstance;

    private string $version = '1.0.0';

    /**
     * Telegram constructor
     *
     * @param array $config
     *
     * @throws InvalidConfigException
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        if (empty($this->api_key)) {
            throw new InvalidConfigException('API KEY not defined!');
        }
        preg_match('/(\d+):[\w\-]+/', $this->api_key, $matches);
        if (!isset($matches[1])) {
            throw new InvalidConfigException('Invalid API KEY defined!');
        }

        $this->bot_id  = $matches[1];
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        //Add default commands path
        $class = get_class($this);
        if (($pos = strrpos($class, '\\')) !== false) {
            $this->addCommandsNamespace(substr($class, 0, $pos) . '\\commands');
        }

        if (!\Yii::$app->has('tgRequest')) {
            \Yii::$app->set('tgRequest', [
                'class' => Request::class,
                'telegram' => $this,
            ]);
        }

        $this->requestInstance = \Yii::$app->get('tgRequest');
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->requestInstance;
    }

    private function getCommandPath($namespace): string
    {
        return \Yii::getAlias('@' . str_replace('\\', '/', $namespace));
    }

    private function validateCommandClass($commandClass): bool
    {
        if (class_exists($commandClass)) {
            try {
                $class = new \ReflectionClass($commandClass);
            } catch (\Exception) {
                return false;
            }

            if (!$class->isAbstract() && $class->isSubclassOf('onix\telegram\commands\Command')) {
                if ($class->isSubclassOf('onix\telegram\commands\AdminCommand')) {
                    return $this->isAdmin();
                } else {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get commands list
     *
     * @return array $commands
     */
    public function getCommandsList(): array
    {
        $commands = [];

        foreach ($this->commandsNamespaces as $ns) {
            $commandPath = $this->getCommandPath($ns);
            if (is_dir($commandPath)) {
                $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($commandPath, \FilesystemIterator::KEY_AS_PATHNAME));
                $iterator = new \RegexIterator($iterator, '/.*Command\.php$/', \RegexIterator::GET_MATCH);
                foreach ($iterator as $matches) {
                    $file = $matches[0];
                    $relativePath = str_replace($commandPath, '', $file);
                    $class = strtr($relativePath, [
                        '/' => '\\',
                        '.php' => '',
                    ]);

                    $commandClass = $ns . $class;
                    if ($this->validateCommandClass($commandClass)) {
                        $command_name = mb_strtolower($this->sanitizeCommand(substr(basename($file), 0, -11)));

                        try {
                            $commands[$command_name] = \Yii::createObject($commandClass, ['telegram' => $this, 'update' => $this->update]);
                            \Yii::debug(['add command', $commandClass], 'telegram');
                        } catch (InvalidConfigException) {
                        }
                    }
                }
            }
        }

        if (isset($commands['callbackquery'])) {
            /** @var CallbackqueryCommand $callbackQueryCommand */
            $callbackQueryCommand = $commands['callbackquery'];

            foreach ($commands as $name => $command) {
                if ($command instanceof CallbackQueryHandler) {
                    $callbackQueryCommand->addCallbackHandler($name, $command);
                }
            }
        }

        return $commands;
    }

    /**
     * Get an object instance of the passed command
     *
     * @param string $command
     *
     * @return Command|null
     */
    public function getCommandObject(string $command): ?Command
    {
        if (isset($this->commands_objects[$command])) {
            return $this->commands_objects[$command];
        }

        $this->commands_objects = $this->getCommandsList();
        if (isset($this->commands_objects[$command])) {
            return $this->commands_objects[$command];
        }

        return null;
    }

    /**
     * Get the ServerResponse of the last Command execution
     *
     * @return ServerResponse|null
     */
    public function getLastCommandResponse(): ?ServerResponse
    {
        return $this->last_command_response;
    }

    /**
     * Handle getUpdates method
     *
     * @param mixed|null $input
     * @param int|null $limit
     * @param int|null $timeout
     *
     * @return ServerResponse
     *
     * @throws BaseException
     * @throws TelegramException
     */
    public function handleGetUpdates(mixed $input = null, int $limit = null, int $timeout = null): ServerResponse
    {
        if (empty($this->bot_username)) {
            throw new TelegramException('Bot Username is not defined!');
        }

        $offset = 0;

        //Take custom input into account.
        if (!empty($input)) {
            $response = new ServerResponse($input);
        } else {
            $last_update = Storage::telegramUpdateSelect();
            if ($last_update !== null) {
                //Get last update id from the database
                $this->last_update_id = $last_update->id;
            }

            if ($this->last_update_id !== null) {
                // As explained in the telegram bot API documentation
                $offset = $this->last_update_id + 1;
            }

            $response = $this->requestInstance->getUpdates([
                'offset' => $offset,
                'limit' => $limit,
                'timeout' => $timeout,
            ]);
        }

        if ($response->isOk()) {
            $results = $response->result;

            //Process all updates
            /** @var Update $result */
            foreach ($results as $result) {
                $this->processUpdate($result);
            }
        }

        return $response;
    }

    /**
     * Handle bot request from webhook
     *
     * @param string $input
     *
     * @return bool
     *
     * @throws BaseException
     * @throws TelegramException
     */
    public function handle(string $input): bool
    {
        if (empty($this->bot_username)) {
            throw new TelegramException('Bot Username is not defined!');
        }

        if (empty($input)) {
            throw new TelegramException('Input is empty!');
        }

        $post = Json::decode($input);
        if (empty($post)) {
            throw new TelegramException('Invalid JSON!');
        }

        if ($response = $this->processUpdate(new Update($post))) {
            return $response->isOk();
        }

        return false;
    }

    /**
     * Get the command name from the command type
     *
     * @param string $type
     *
     * @return string
     */
    protected function getCommandFromType(string $type): string
    {
        return $this->ucfirstUnicode(str_replace('_', '', $type));
    }

    /**
     * Process bot Update request
     *
     * @param Update $update
     *
     * @return ServerResponse|null
     *
     * @throws BaseException
     * @throws TelegramException
     */
    public function processUpdate(Update $update): ?ServerResponse
    {
        $this->update = $update;
        $this->last_update_id = $update->updateId;

        if (is_callable($this->update_filter)) {
            $reason = 'Update denied by update_filter';
            try {
                $allowed = (bool) call_user_func_array($this->update_filter, [$update, $this, &$reason]);
            } catch (\Exception) {
                $allowed = false;
            }

            if (!$allowed) {
                \Yii::debug($reason, 'telegram');
                return new ServerResponse(['ok' => false, 'description' => 'denied']);
            }
        }

        //Make sure we have an up-to-date command list
        //This is necessary to "require" all the necessary command files!
        $this->commands_objects = $this->getCommandsList();

        //If all else fails, it's a generic message.
        $command = self::GENERIC_MESSAGE_COMMAND;

        $update_type = $this->update->getUpdateType();
        if ($update_type === 'message') {
            $message = $this->update->message;
            $type = $message->getType();

            // Let's check if the message object has the type field we're looking for...
            $command_tmp = $type === 'command' ? $message->getCommand() : $this->getCommandFromType($type);
            // ...and if a fitting command class is available.
            $command_obj = $this->getCommandObject($command_tmp);

            // Empty usage string denotes a non-executable command.
            if (($command_obj === null && $type === 'command') ||
                ($command_obj !== null && $command_obj->getUsage() !== '')
            ) {
                $command = $command_tmp;
            }
        } else {
            $command = $this->getCommandFromType($update_type);
        }

        //Make sure we don't try to process update that was already processed
        $last_update = Storage::telegramUpdateSelect($this->update->updateId);
        if ($last_update !== null) {
            \Yii::debug('Duplicate update received, processing aborted!', 'telegram');
            return $this->requestInstance->emptyResponse();
        }

        Storage::insertUpdateRequest($this->update);

        return $this->executeCommand($command);
    }

    /**
     * Execute /command
     *
     * @param string $command
     *
     * @return ServerResponse|null
     *
     * @throws TelegramException
     */
    public function executeCommand(string $command): ?ServerResponse
    {
        $command = mb_strtolower($command);

        $command_obj = $this->commands_objects[$command] ?? $this->getCommandObject($command);

        if (!$command_obj || !$command_obj->isEnabled()) {
            //Failsafe in case the Generic command can't be found
            if ($command === self::GENERIC_COMMAND) {
                throw new TelegramException('Generic command missing!');
            }

            //Handle a generic command or non-existing one
            $this->last_command_response = $this->executeCommand(self::GENERIC_COMMAND);
        } else {
            // execute() method is executed after preExecute()
            // This is to prevent executing a DB query without a valid connection
            $command_obj->setUpdate($this->update);
            $this->last_command_response = $command_obj->preExecute();
        }

        return $this->last_command_response;
    }

    /**
     * Sanitize Command
     *
     * @param string $command
     *
     * @return string
     */
    protected function sanitizeCommand(string $command): string
    {
        return str_replace(' ', '', $this->ucwordsUnicode(str_replace('_', ' ', $command)));
    }

    /**
     * Enable a single Admin account
     *
     * @param integer|string $admin_id Single admin id
     *
     * @return Telegram
     */
    public function enableAdmin(int|string $admin_id): static
    {
        $admin_id = intval($admin_id);
        if ($admin_id <= 0) {
            \Yii::error('Invalid value "' . $admin_id . '" for admin.', 'telegram');
        } elseif (!in_array($admin_id, $this->admins, true)) {
            $this->admins[] = $admin_id;
        }

        return $this;
    }

    /**
     * Enable a list of Admin Accounts
     *
     * @param array $admin_ids List of admin ids
     *
     * @return Telegram
     */
    public function enableAdmins(array $admin_ids): static
    {
        foreach ($admin_ids as $admin_id) {
            $this->enableAdmin($admin_id);
        }

        return $this;
    }

    /**
     * Get list of admins
     *
     * @return array
     */
    public function getAdminList(): array
    {
        return $this->admins;
    }

    /**
     * Check if the passed user is an admin
     *
     * If no user id is passed, the current update is checked for a valid message sender.
     *
     * @param int|null $user_id
     *
     * @return bool
     */
    public function isAdmin(int $user_id = null): bool
    {
        if ($user_id === null && $this->update !== null) {
            //Try to figure out if the user is an admin
            $update_props = [
                'message',
                'editedMessage',
                'channelPost',
                'editedChannelPost',
                'inlineQuery',
                'chosenInlineResult',
                'callbackQuery',
            ];

            foreach ($update_props as $prop) {
                $object = $this->update->$prop;
                if ($object !== null && $from = $object->from) {
                    $user_id = $from->id;
                    break;
                }
            }
        }

        return !($user_id === null) && in_array($user_id, $this->admins, true);
    }

    /**
     * Add a single custom commands namespace
     *
     * @param string $ns Custom commands namespace to add
     * @param bool $before If the path should be prepended or appended to the list
     *
     * @return Telegram
     */
    public function addCommandsNamespace(string $ns, bool $before = true): static
    {
        if (!in_array($ns, $this->commandsNamespaces, true)) {
            if ($before) {
                array_unshift($this->commandsNamespaces, $ns);
            } else {
                $this->commandsNamespaces[] = $ns;
            }
        }

        return $this;
    }

    /**
     * Add multiple custom commands paths
     *
     * @param array $namespaces Custom commands paths to add
     * @param bool $before If the paths should be prepended or appended to the list
     *
     * @return Telegram
     */
    public function addCommandsNamepaces(array $namespaces, bool $before = true): static
    {
        foreach ($namespaces as $ns) {
            $this->addCommandsNamespace($ns, $before);
        }

        return $this;
    }

    /**
     * Return the list of commands namespaces
     *
     * @return array
     */
    public function getCommandsNamespaces(): array
    {
        return $this->commandsNamespaces;
    }

    /**
     * Set command config
     *
     * Provide further variables to a particular commands.
     * For example, you can add the channel name at the command /sendtochannel
     * Or you can add the api key for external service.
     *
     * @param string $command
     * @param array $config
     *
     * @return Telegram
     */
    public function setCommandConfig(string $command, array $config): static
    {
        $this->commands_config[$command] = $config;

        return $this;
    }

    /**
     * Get command config
     *
     * @param string $command
     *
     * @return array
     */
    public function getCommandConfig(string $command): array
    {
        return $this->commands_config[$command] ?? [];
    }

    /**
     * Clear all config
     */
    public function clearCommandsConfig(): void
    {
        $this->commands_config = [];
    }

    /**
     * Get API key
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->api_key;
    }

    /**
     * Get Bot name
     *
     * @return string
     */
    public function getBotUsername(): string
    {
        return $this->bot_username;
    }

    /**
     * Get Bot Id
     *
     * @return string
     */
    public function getBotId(): string
    {
        return $this->bot_id;
    }

    /**
     * Set Webhook for bot
     *
     * @param string $url
     * @param array $data Optional parameters.
     *
     * @return ServerResponse
     *
     * @throws TelegramException
     */
    public function setWebhook(string $url, array $data = []): ServerResponse
    {
        if (empty($url)) {
            throw new TelegramException('Hook url is empty!');
        }

        $data = array_intersect_key($data, array_flip([
            'certificate',
            'max_connections',
            'allowed_updates',
        ]));
        $data['url'] = $url;

        // If the certificate is passed as a path, encode and add the file to the data array.
        if (!empty($data['certificate']) && is_string($data['certificate'])) {
            $data['certificate'] = new \CURLFile($data['certificate']);
        }

        $result = $this->requestInstance->setWebhook($data);

        if (!$result->isOk()) {
            throw new TelegramException(
                'Webhook was not set! Error: ' . $result->errorCode . ' ' . $result->description
            );
        }

        return $result;
    }

    /**
     * Delete any assigned webhook
     *
     * @return ServerResponse
     *
     * @throws TelegramException
     */
    public function deleteWebhook(): ServerResponse
    {
        $result = $this->requestInstance->deleteWebhook();

        if (!$result->isOk()) {
            throw new TelegramException(
                'Webhook was not deleted! Error: ' . $result->errorCode . ' ' . $result->description
            );
        }

        return $result;
    }

    /**
     * Replace function `ucwords` for UTF-8 characters in the class definition and commands
     *
     * @param string $str
     * @param string $encoding (default = 'UTF-8')
     *
     * @return string
     */
    protected function ucwordsUnicode(string $str, string $encoding = 'UTF-8'): string
    {
        return mb_convert_case($str, MB_CASE_TITLE, $encoding);
    }

    /**
     * Replace function `ucfirst` for UTF-8 characters in the class definition and commands
     *
     * @param string $str
     * @param string $encoding (default = 'UTF-8')
     *
     * @return string
     */
    protected function ucfirstUnicode(string $str, string $encoding = 'UTF-8'): string
    {
        return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding)
            . mb_strtolower(mb_substr($str, 1, mb_strlen($str), $encoding), $encoding);
    }

    /**
     * Enable requests limiter
     *
     * @param array $options
     *
     * @return Telegram
     * @throws TelegramException
     */
    public function enableLimiter(array $options = []): static
    {
        $this->requestInstance->setLimiter(true, $options);

        return $this;
    }

    /**
     * Run provided commands
     *
     * @param array $commands
     *
     * @throws BaseException
     * @throws TelegramException
     */
    public function runCommands(array $commands): void
    {
        if (empty($commands)) {
            throw new TelegramException('No command(s) provided!');
        }

        $this->run_commands = true;

        $result = $this->requestInstance->getMe();

        if ($result->isOk()) {
            $result = $result->result;

            $bot_id = $result->id;
            $bot_name = $result->firstName;
            $bot_username = $result->username;
        } else {
            $bot_id = $this->getBotId();
            $bot_name = $this->getBotUsername();
            $bot_username = $this->getBotUsername();
        }

        // Give bot access to admin commands
        $this->enableAdmin($bot_id);

        $newUpdate = static function ($text = '') use ($bot_id, $bot_name, $bot_username) {
            return new Update(
                [
                    'update_id' => 0,
                    'message' => [
                        'message_id' => 0,
                        'from' => [
                            'id' => $bot_id,
                            'first_name' => $bot_name,
                            'username' => $bot_username,
                        ],
                        'date' => time(),
                        'chat' => [
                            'id' => $bot_id,
                            'type' => 'private',
                        ],
                        'text' => $text,
                    ],
                ]
            );
        };

        $this->update = $newUpdate(); // Required for isAdmin() check inside getCommandObject()
        $this->commands_objects = $this->getCommandsList(); // Load up-to-date commands list

        foreach ($commands as $command) {
            $this->update = $newUpdate($command);

            $this->executeCommand($this->update->message->command);
        }
    }

    /**
     * Is this session initiated by runCommands()
     *
     * @return bool
     */
    public function isRunCommands(): bool
    {
        return $this->run_commands;
    }

    /**
     * Return last update id
     *
     * @return int|null
     */
    public function getLastUpdateId(): ?int
    {
        return $this->last_update_id;
    }

    /**
     * Set an update filter callback
     *
     * @param callable $callback
     *
     * @return Telegram
     */
    public function setUpdateFilter(callable $callback): static
    {
        $this->update_filter = $callback;

        return $this;
    }

    /**
     * Return update filter callback
     *
     * @return callable|null
     */
    public function getUpdateFilter(): ?callable
    {
        return $this->update_filter;
    }

    public function getVersion(): string
    {
        return $this->version;
    }
}
