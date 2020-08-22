<?php
namespace onix\telegram\commands;

use onix\telegram\Conversation;
use onix\telegram\entities\ServerResponse;
use onix\telegram\exceptions\TelegramException;
use yii\base\Exception as BaseException;
use yii\base\InvalidConfigException;

abstract class SystemCommand extends Command
{
    /**
     * @var bool Try to execute any deprecated system command.
     */
    public static $execute_deprecated = false;

    /**
     * @{inheritdoc}
     *
     * Set to empty string to disallow users calling system commands.
     */
    protected $usage = '';

    /**
     * A system command just executes
     *
     * Although system commands should just work and return a successful ServerResponse,
     * each system command can override this method to add custom functionality.
     *
     * @return ServerResponse
     */
    public function execute()
    {
        // System command, return empty ServerResponse by default
        return $this->request->emptyResponse();
    }

    /**
     * Method to execute any active conversation.
     *
     * @return ServerResponse|null
     * @throws TelegramException
     * @throws BaseException
     * @internal
     */
    protected function executeActiveConversation()
    {
        $message = $this->message;
        if ($message === null) {
            return null;
        }

        $user = $message->from;
        $chat = $message->chat;
        if ($user === null || $chat === null) {
            return null;
        }

        // If a conversation is busy, execute the conversation command after handling the message.
        $conversation = new Conversation(['user_id' => $user->id, 'chat_id' => $chat->id]);

        // Fetch conversation command if it exists and execute it.
        if ($conversation->exists() && ($command = $conversation->commandText)) {
            return $this->telegram->executeCommand($command);
        }

        return null;
    }

    /**
     * BC helper method to execute deprecated system commands.
     *
     * @return ServerResponse|null
     *
     * @throws TelegramException
     * @throws InvalidConfigException
     * @internal
     */
    protected function executeDeprecatedSystemCommand()
    {
        $message = $this->message;
        if ($message === null) {
            return null;
        }

        // List of service messages previously handled internally.
        $service_message_getters = [
            'newchatmembers' => 'getNewChatMembers',
            'leftchatmember' => 'getLeftChatMember',
            'newchattitle' => 'getNewChatTitle',
            'newchatphoto' => 'getNewChatPhoto',
            'deletechatphoto' => 'getDeleteChatPhoto',
            'groupchatcreated' => 'getGroupChatCreated',
            'supergroupchatcreated' => 'getSupergroupChatCreated',
            'channelchatcreated' => 'getChannelChatCreated',
            'migratefromchatid' => 'getMigrateFromChatId',
            'migratetochatid' => 'getMigrateToChatId',
            'pinnedmessage' => 'getPinnedMessage',
            'successfulpayment' => 'getSuccessfulPayment',
        ];

        foreach ($service_message_getters as $command => $service_message_getter) {
            // Let's check if this message is a service message.
            if ($message->$service_message_getter() === null) {
                continue;
            }

            // Make sure the command exists otherwise GenericCommand would be executed.
            if ($this->telegram->getCommandObject($command) === null) {
                break;
            }

            return $this->telegram->executeCommand($command);
        }

        return null;
    }
}
