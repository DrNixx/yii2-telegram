<?php
namespace onix\telegram\commands\system;

use onix\telegram\commands\SystemCommand;
use onix\telegram\entities\ServerResponse;
use onix\telegram\exceptions\TelegramException;
use onix\telegram\Telegram;
use yii\base\Exception as BaseException;

/**
 * Generic message command
 */
class GenericmessageCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = Telegram::GENERIC_MESSAGE_COMMAND;

    /**
     * @var string
     */
    protected $description = 'Handle generic message';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * Execute command
     *
     * @return ServerResponse
     * @throws TelegramException
     * @throws BaseException
     */
    public function execute()
    {
        // Try to continue any active conversation.
        if ($active_conversation_response = $this->executeActiveConversation()) {
            return $active_conversation_response;
        }

        // Try to execute any deprecated system commands.
        if (self::$execute_deprecated &&
            $deprecated_system_command_response = $this->executeDeprecatedSystemCommand()
        ) {
            return $deprecated_system_command_response;
        }

        return $this->request->emptyResponse();
    }
}
