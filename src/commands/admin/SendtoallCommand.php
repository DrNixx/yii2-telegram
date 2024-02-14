<?php
namespace onix\telegram\commands\admin;

use onix\telegram\commands\AdminCommand;
use onix\telegram\entities\Message;
use onix\telegram\entities\ServerResponse;
use onix\telegram\exceptions\TelegramException;
use yii\base\Exception as BaseException;

/**
 * Admin "/sendtoall" command
 */
class SendtoallCommand extends AdminCommand
{
    /**
     * @var string
     */
    protected $name = 'sendtoall';

    /**
     * @var string
     */
    protected $description = 'Send the message to all of the bot\'s users';

    /**
     * @var string
     */
    protected $usage = '/sendtoall <message to send>';

    /**
     * @var string
     */
    protected $version = '1.5.0';

    /**
     * Execute command
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $text = $this->message->getMessageText(true);

        if ($text === '') {
            return $this->replyToChat('Usage: ' . $this->getUsage());
        }

        /** @var ServerResponse[] $results */
        $results = $this->request->sendToActiveChats(
            'sendMessage',     //callback function to execute (see Request.php methods)
            ['text' => $text], //Param to evaluate the request
            [
                'groups' => true,
                'supergroups' => true,
                'channels' => false,
                'users' => true,
            ]
        );

        if (empty($results)) {
            return $this->replyToChat('No users or chats found.');
        }

        $total  = 0;
        $failed = 0;

        $text = 'Message sent to:' . PHP_EOL;

        foreach ($results as $result) {
            $name = '';
            $type = '';
            if ($result->isOk()) {
                $status = '✔️';

                /** @var Message $message */
                $message = $result->result;
                $chat    = $message->chat;
                if ($chat->isPrivateChat()) {
                    $name = $chat->firstName;
                    $type = 'user';
                } else {
                    $name = $chat->title;
                    $type = 'chat';
                }
            } else {
                $status = '✖️';
                ++$failed;
            }
            ++$total;

            $text .= $total . ') ' . $status . ' ' . $type . ' ' . $name . PHP_EOL;
        }
        $text .= 'Delivered: ' . ($total - $failed) . '/' . $total . PHP_EOL;

        return $this->replyToChat($text);
    }
}
