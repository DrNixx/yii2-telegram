<?php
namespace onix\telegram\commands\admin;

use onix\telegram\commands\AdminCommand;
use onix\telegram\exceptions\TelegramException;
use Exception;
use Yii;
use yii\helpers\Json;

/**
 * Admin "/debug" command
 */
class DebugCommand extends AdminCommand
{
    /**
     * @var string
     */
    protected $name = 'debug';

    /**
     * @var string
     */
    protected $description = 'Debug command to help find issues';

    /**
     * @var string
     */
    protected $usage = '/debug';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * Command execute method
     *
     * @return mixed
     * @throws TelegramException
     */
    public function execute()
    {
        $message = $this->message;
        $chat = $message->chat;
        $text = strtolower($message->getMessageText(true));

        $data = ['chat_id' => $chat->id];

        if ($text !== 'glasnost' && !$chat->isPrivateChat()) {
            $data['text'] = 'Only available in a private chat.';

            return $this->request->sendMessage($data);
        }

        $debug_info = [];

        $debug_info[] = sprintf('*TelegramBot version:* `%s`', $this->telegram->getVersion());
        $debug_info[] = sprintf('*Download path:* `%s`', $this->telegram->getDownloadPath() ?: '`_Not set_`');
        $debug_info[] = sprintf('*Upload path:* `%s`', $this->telegram->getUploadPath() ?: '`_Not set_`');

        // Commands paths.
        $debug_info[] = '*Commands namespaces:*';
        $debug_info[] = sprintf(
            '```' . PHP_EOL . '%s```',
            Json::encode($this->telegram->getCommandsNamespaces(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        $php_bit = '';
        PHP_INT_SIZE === 4 && $php_bit = ' (32bit)';
        PHP_INT_SIZE === 8 && $php_bit = ' (64bit)';
        $debug_info[] = sprintf('*PHP version:* `%1$s%2$s; %3$s; %4$s`', PHP_VERSION, $php_bit, PHP_SAPI, PHP_OS);
        $debug_info[] = sprintf('*Maximum PHP script execution time:* `%d seconds`', ini_get('max_execution_time'));

        $mysql_version = Yii::$app->db->driverName;
        $debug_info[]  = sprintf('*DB driver:* `%s`', $mysql_version ?: 'disabled');

        $debug_info[] = sprintf('*Operating System:* `%s`', php_uname());

        if (isset($_SERVER['SERVER_SOFTWARE'])) {
            $debug_info[] = sprintf('*Web Server:* `%s`', $_SERVER['SERVER_SOFTWARE']);
        }
        if (function_exists('curl_init')) {
            $curlversion  = curl_version();
            $debug_info[] = sprintf(
                '*curl version:* `%1$s; %2$s`',
                $curlversion['version'],
                $curlversion['ssl_version']
            );
        }

        $webhook_info_title = '*Webhook Info:*';
        try {
            // Check if we're actually using the Webhook method.
            if ($this->request->getInput() === '') {
                $debug_info[] = $webhook_info_title . ' `Using getUpdates method, not Webhook.`';
            } else {
                $webhook_info_result = Json::decode($this->request->getWebhookInfo(), true)['result'];
                // Add a human-readable error date string if necessary.
                if (isset($webhook_info_result['last_error_date'])) {
                    $webhook_info_result['last_error_date_string'] =
                        date('Y-m-d H:i:s', $webhook_info_result['last_error_date']);
                }

                $webhook_info_result_str =
                    Json::encode($webhook_info_result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                $debug_info[] = $webhook_info_title;
                $debug_info[] = sprintf(
                    '```' . PHP_EOL . '%s```',
                    $webhook_info_result_str
                );
            }
        } catch (Exception $e) {
            $debug_info[] = $webhook_info_title . sprintf(' `Failed to get webhook info! (%s)`', $e->getMessage());
        }

        $data['parse_mode'] = 'Markdown';
        $data['text']       = implode(PHP_EOL, $debug_info);

        return $this->request->sendMessage($data);
    }
}
