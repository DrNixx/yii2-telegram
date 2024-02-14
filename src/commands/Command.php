<?php
namespace onix\telegram\commands;

use onix\telegram\entities\CallbackQuery;
use onix\telegram\entities\ChatMemberUpdated;
use onix\telegram\entities\ChosenInlineResult;
use onix\telegram\entities\InlineQuery;
use onix\telegram\entities\Message;
use onix\telegram\entities\payments\PreCheckoutQuery;
use onix\telegram\entities\payments\ShippingQuery;
use onix\telegram\entities\Poll;
use onix\telegram\entities\ServerResponse;
use onix\telegram\entities\Update;
use onix\telegram\exceptions\TelegramException;
use onix\telegram\Request;
use onix\telegram\Telegram;
use Yii;
use yii\base\BaseObject;
use yii\base\Exception as BaseException;
use yii\base\InvalidConfigException;
use yii\base\UnknownPropertyException;
use yii\helpers\ArrayHelper;

/**
 * Class Command
 *
 * Base class for commands. It includes some helper methods that can fetch data directly from the Update object.
 *
 * @property-read Message $message Optional. New incoming message of any kind — text, photo, sticker, etc.
 * @property-read Message $editedMessage Optional. New version of a message that is known to the bot and was edited
 * @property-read Message $channelPost Optional. New post in the channel, can be any kind — text, photo, sticker, etc.
 * @property-read Message $editedChannelPost Optional. New version of a post in the channel that is known to
 * the bot and was edited
 * @property-read ChatMemberUpdated $myChatMember
 * @property-read ChatMemberUpdated $chatMember
 *
 * @property-read InlineQuery $inlineQuery Optional. New incoming inline query
 * @property-read ChosenInlineResult $chosenInlineResult Optional. The result of an inline query that was chosen
 * by a user and sent to their chat partner.
 *
 * @property-read CallbackQuery $callbackQuery Optional. New incoming callback query
 * @property-read ShippingQuery $shippingQuery Optional. New incoming shipping query.
 * Only for invoices with flexible price
 *
 * @property-read PreCheckoutQuery $preCheckoutQuery Optional. New incoming pre-checkout query.
 * Contains full information about checkout
 *
 * @property-read Poll $poll Optional. New poll state. Bots receive only updates about polls, which are sent
 * or stopped by the bot
 *
 * @property-read Telegram $telegram
 *
 * @property-read string $i18nCategory Category for message translator
 * @property-read string $name Command name
 * @property-read string $category Command category
 * @property-read string $description Command description
 * @property-read string $version Command version
 * @property-read array $config Command config
 * @property-read Update $update
 */
abstract class Command extends BaseObject
{
    /**
     * Telegram object
     *
     * @var Telegram
     */
    private $telegram;

    /**
     * Update object
     *
     * @var Update
     */
    protected $update;

    /**
     * Name
     *
     * @var string
     */
    protected $name = '';

    /**
     * Category
     *
     * @var string
     */
    protected $category = '';

    /**
     * Description
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Usage
     *
     * @var string
     */
    protected $usage = 'Command usage';

    /**
     * Show in Help
     *
     * @var bool
     */
    protected $show_in_help = true;

    /**
     * Version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * If this command is enabled
     *
     * @var boolean
     */
    protected $enabled = true;

   /**
    * Make sure this command only executes on a private chat.
    *
    * @var bool
    */
    protected $private_only = false;

    /**
     * Command config
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var Request
     */
    protected $request;

    /**
     * @inheritDoc
     *
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->config = $this->getTelegram()->getCommandConfig($this->name);
        $this->request = $this->getTelegram()->request;
    }

    /**
     * @return Telegram
     *
     * @throws InvalidConfigException
     */
    public function getTelegram()
    {
        if (empty($this->telegram)) {
            $this->telegram = Yii::$app->get('telegram');
        }

        return $this->telegram;
    }

    /**
     * @param Telegram $value
     */
    public function setTelegram(Telegram $value)
    {
        $this->telegram = $value;
    }

    /**
     * Get update object
     *
     * @return Update
     */
    public function getUpdate()
    {
        return $this->update;
    }

    /**
     * Set update object
     *
     * @param Update|null $update
     *
     * @return Command
     */
    public function setUpdate(Update $update = null)
    {
        if ($update !== null) {
            $this->update = $update;
        }

        return $this;
    }

    /**
     * Pre-execute command
     *
     * @return ServerResponse
     *
     * @throws TelegramException
     */
    public function preExecute()
    {
        if ($this->isPrivateOnly() && $this->removeNonPrivateMessage()) {
            $message = $this->message;

            if ($user = $message->from) {
                return $this->request->sendMessage([
                    'chat_id' => $user->id,
                    'parse_mode' => 'Markdown',
                    'text' => sprintf(
                        "/%s command is only available in a private chat.\n(`%s`)",
                        $this->name,
                        $message->text
                    ),
                ]);
            }

            return $this->request->emptyResponse();
        }

        return $this->execute();
    }

    /**
     * Execute command
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    abstract public function execute();

    /**
     * @inheritDoc
     */
    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (UnknownPropertyException $e) {
            if ($this->update === null) {
                return null;
            }

            return $this->update->$name;
        }
    }

    /**
     * @inheritDoc
     */
    public function __set($name, $value)
    {
        try {
            parent::__set($name, $value);
        } catch (UnknownPropertyException $e) {
            if ($this->update !== null) {
                $this->update->$name = $value;
            }
        }
    }

    /**
     * Get command config
     *
     * Look for config $name if found return it, if not return null.
     * If $name is not set return all set config.
     *
     * @param string|null $name
     *
     * @return array|mixed|null
     */
    public function getConfig($name = null)
    {
        if ($name === null) {
            return $this->config;
        }
        if (isset($this->config[$name])) {
            return $this->config[$name];
        }

        return null;
    }

    /**
     * Get category for message translator
     *
     * @return string
     */
    protected function getI18nCategory()
    {
        $key = 'telegram_cmd';
        if (!empty($this->category)) {
            $key .= '_' . strtolower($this->category);
        }

        return $key;
    }

    /**
     * Get usage
     *
     * @return string
     */
    public function getUsage()
    {
        return Yii::t($this->getI18nCategory(), $this->usage);
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return Yii::t($this->getI18nCategory(), $this->category);
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return Yii::t($this->getI18nCategory(), $this->description);
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get Show in Help
     *
     * @return bool
     */
    public function showInHelp()
    {
        return $this->show_in_help;
    }

    /**
     * Check if command is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * If this command is intended for private chats only.
     *
     * @return bool
     */
    public function isPrivateOnly()
    {
        return $this->private_only;
    }

    /**
     * If this is a SystemCommand
     *
     * @return bool
     */
    public function isSystemCommand()
    {
        return ($this instanceof SystemCommand);
    }

    /**
     * If this is an AdminCommand
     *
     * @return bool
     */
    public function isAdminCommand()
    {
        return ($this instanceof AdminCommand);
    }

    /**
     * If this is a UserCommand
     *
     * @return bool
     */
    public function isUserCommand()
    {
        return ($this instanceof UserCommand);
    }

    /**
     * Delete the current message if it has been called in a non-private chat.
     *
     * @return bool
     */
    protected function removeNonPrivateMessage()
    {
        $message = $this->message ?: $this->editedMessage;

        if ($message) {
            $chat = $message->chat;

            if (!$chat->isPrivateChat()) {
                // Delete the falsely called command message.
                $this->request->deleteMessage([
                    'chat_id' => $chat->id,
                    'message_id' => $message->messageId,
                ]);

                return true;
            }
        }

        return false;
    }

    /**
     * Set response language
     *
     * @param Message|null $message
     */
    protected function setReplyLanguage(?Message $message = null)
    {
    }

    /**
     * Helper to reply to a chat directly.
     *
     * @param string $text
     * @param array $data
     *
     * @return ServerResponse
     *
     * @throws TelegramException
     */
    public function replyToChat(string $text, array $data = []): ServerResponse
    {
        if ($message = $this->message ?:
            $this->editedMessage ?:
            $this->channelPost ?:
            $this->editedChannelPost ?:
            $this->myChatMember ?:
            $this->chatMember
        ) {
            return $this->request->sendMessage(ArrayHelper::merge([
                'chat_id' => $message->chat->id,
                'text' => $text,
            ], $data));
        }

        return $this->request->emptyResponse();
    }

    /**
     * Helper to reply to a user directly.
     *
     * @param string $text
     * @param array $data
     *
     * @return ServerResponse
     *
     * @throws TelegramException
     * @throws BaseException
     */
    public function replyToUser(string $text, array $data = [])
    {
        if ($message = $this->message ?: $this->editedMessage) {
            return $this->request->sendMessage(ArrayHelper::merge([
                'chat_id' => $message->from->id,
                'text' => $text,
            ], $data));
        }

        return $this->request->emptyResponse();
    }
}
