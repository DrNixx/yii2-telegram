<?php
namespace onix\telegram\commands\admin;

use onix\telegram\commands\AdminCommand;
use onix\telegram\Conversation;
use onix\telegram\entities\Chat;
use onix\telegram\entities\Keyboard;
use onix\telegram\entities\Message;
use onix\telegram\entities\ServerResponse;
use onix\telegram\exceptions\TelegramException;
use yii\base\Exception as BaseException;

class SendtochannelCommand extends AdminCommand
{
    /**
     * @var string
     */
    protected $name = 'sendtochannel';

    /**
     * @var string
     */
    protected $description = 'Send message to a channel';

    /**
     * @var string
     */
    protected $usage = '/sendtochannel <message to send>';

    /**
     * @var string
     */
    protected $version = '0.3.0';

    /**
     * Conversation Object
     *
     * @var Conversation
     */
    protected $conversation;

    /**
     * Command execute method
     *
     * @return ServerResponse|mixed
     * @throws TelegramException
     * @throws BaseException
     */
    public function execute()
    {
        $message = $this->message;
        $chat_id = $message->chat->id;
        $user_id = $message->from->id;

        $type = $message->getType();
        // 'Cast' the command type to message to protect the machine state
        // if the command is recalled when the conversation is already started
        in_array($type, ['command', 'text'], true) && $type = 'message';

        $text           = trim($message->getMessageText(true));
        $text_yes_or_no = ($text === 'Yes' || $text === 'No');

        $data = [
            'chat_id' => $chat_id,
        ];

        // Conversation
        $this->conversation = new Conversation(['user_id' => $user_id, 'chat_id' => $chat_id, 'command' => $this->getName()]);

        $notes = &$this->conversation->notes;
        !is_array($notes) && $notes = [];

        $channels = (array) $this->getConfig('your_channel');
        if (isset($notes['state'])) {
            $state = $notes['state'];
        } else {
            $state                    = (count($channels) === 0) ? -1 : 0;
            $notes['last_message_id'] = $message->messageId;
        }

        $yes_no_keyboard = new Keyboard(
            [
                'keyboard' => [['Yes', 'No']],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
                'selective' => true,
            ]
        );

        switch ($state) {
            case -1:
                // getConfig has not been configured asking for channel to administer
                if ($type !== 'message' || $text === '') {
                    $notes['state'] = -1;
                    $this->conversation->update();

                    $result = $this->replyToChat(
                        'Insert the channel name or ID (_@yourchannel_ or _-12345_)',
                        [
                            'parse_mode' => 'markdown',
                            'reply_markup' => Keyboard::remove(['selective' => true]),
                        ]
                    );

                    break;
                }
                $notes['channel']         = $text;
                $notes['last_message_id'] = $message->messageId;
                // Jump to state 1
                goto insert;

            default:
            case 0:
                // getConfig has been configured choose channel
                if ($type !== 'message' || $text === '') {
                    $notes['state'] = 0;
                    $this->conversation->update();

                    $keyboard = array_map(function ($channel) {
                        return [$channel];
                    }, $channels);

                    $result = $this->replyToChat(
                        'Choose a channel from the keyboard' . PHP_EOL .
                        '_or_ insert the channel name or ID (_@yourchannel_ or _-12345_)',
                        [
                            'parse_mode' => 'markdown',
                            'reply_markup' => new Keyboard(
                                [
                                    'keyboard' => $keyboard,
                                    'resize_keyboard' => true,
                                    'one_time_keyboard' => true,
                                    'selective' => true,
                                ]
                            ),
                        ]
                    );
                    break;
                }
                $notes['channel']         = $text;
                $notes['last_message_id'] = $message->messageId;

            // no break
            case 1:
                insert:
                if (($type === 'message' && $text === '') || $notes['last_message_id'] === $message->messageId) {
                    $notes['state'] = 1;
                    $this->conversation->update();

                    $result = $this->replyToChat(
                        'Insert the content you want to share: text, photo, audio...',
                        ['reply_markup' => Keyboard::remove(['selective' => true])]
                    );
                    break;
                }
                $notes['last_message_id'] = $message->messageId;
                $notes['message'] = $message->jsonSerialize();
                $notes['message_type'] = $type;
            // no break
            case 2:
                if (!$text_yes_or_no || $notes['last_message_id'] === $message->messageId) {
                    $notes['state'] = 2;
                    $this->conversation->update();

                    // Grab any existing caption.
                    if ($caption = $message->caption) {
                        $notes['caption'] = $caption;
                        $text             = 'No';
                    } elseif (in_array($notes['message_type'], ['video', 'photo'], true)) {
                        $text = 'Would you like to insert a caption?';
                        if (!$text_yes_or_no && $notes['last_message_id'] !== $message->messageId) {
                            $text .= PHP_EOL . 'Type Yes or No';
                        }

                        $result = $this->replyToChat(
                            $text,
                            ['reply_markup' => $yes_no_keyboard]
                        );
                        break;
                    }
                }
                $notes['set_caption']     = ($text === 'Yes');
                $notes['last_message_id'] = $message->messageId;
            // no break
            case 3:
                if ($notes['set_caption'] &&
                    ($notes['last_message_id'] === $message->messageId || $type !== 'message')
                ) {
                    $notes['state'] = 3;
                    $this->conversation->update();

                    $result = $this->replyToChat(
                        'Insert caption:',
                        ['reply_markup' => Keyboard::remove(['selective' => true])]
                    );
                    break;
                }
                $notes['last_message_id'] = $message->messageId;
                if (isset($notes['caption'])) {
                    // If caption has already been send with the file, no need to ask for it.
                    $notes['set_caption'] = true;
                } else {
                    $notes['caption'] = $text;
                }
            // no break
            case 4:
                if (!$text_yes_or_no || $notes['last_message_id'] === $message->messageId) {
                    $notes['state'] = 4;
                    $this->conversation->update();

                    $result = $this->replyToChat('Message will look like this:');

                    if ($notes['message_type'] !== 'command') {
                        if ($notes['set_caption']) {
                            $data['caption'] = $notes['caption'];
                        }
                        $this->sendBack(new Message($notes['message']), $data);

                        $data['reply_markup'] = $yes_no_keyboard;

                        $data['text'] = 'Would you like to post it?';
                        if (!$text_yes_or_no && $notes['last_message_id'] !== $message->messageId) {
                            $data['text'] .= PHP_EOL . 'Type Yes or No';
                        }
                        $result = $this->request->sendMessage($data);
                    }
                    break;
                }

                $notes['post_message']    = ($text === 'Yes');
                $notes['last_message_id'] = $message->messageId;
            // no break
            case 5:
                $data['reply_markup'] = Keyboard::remove(['selective' => true]);

                if ($notes['post_message']) {
                    $data['parse_mode'] = 'markdown';
                    $data['text']       = $this->publish(
                        new Message($notes['message']),
                        $notes['channel'],
                        $notes['caption']
                    );
                } else {
                    $data['text'] = 'Aborted by user, message not sent..';
                }

                $this->conversation->stop();
                $result = $this->request->sendMessage($data);
        }

        return $result;
    }

    /**
     * SendBack
     *
     * Received a message, the bot can send a copy of it to another chat/channel.
     * You don't have to care about the type of the message, the function detect it and use the proper
     * REQUEST:: function to send it.
     * $data include all the var that you need to send the message to the proper chat
     *
     * @param Message $message
     * @param array $data
     *
     * @return ServerResponse
     *
     * @throws TelegramException*@throws BaseException
     * @todo Looking for a more significant name
     *
     * @todo This method will be moved to a higher level maybe in AdminCommand or Command
     */
    protected function sendBack(Message $message, array $data)
    {
        $type = $message->getType();
        in_array($type, ['command', 'text'], true) && $type = 'message';

        if ($type === 'message') {
            $data['text'] = $message->getText(true);
        } elseif ($type === 'audio') {
            $data['audio']     = $message->audio->fileId;
            $data['duration']  = $message->audio->duration;
            $data['performer'] = $message->audio->performer;
            $data['title']     = $message->audio->title;
        } elseif ($type === 'document') {
            $data['document'] = $message->document->fileId;
        } elseif ($type === 'photo') {
            $data['photo'] = $message->photo[0]->fileId;
        } elseif ($type === 'sticker') {
            $data['sticker'] = $message->sticker->fileId;
        } elseif ($type === 'video') {
            $data['video'] = $message->video->fileId;
        } elseif ($type === 'voice') {
            $data['voice'] = $message->voice->fileId;
        } elseif ($type === 'location') {
            $data['latitude']  = $message->location->latitude;
            $data['longitude'] = $message->location->longitude;
        }

        return $this->request->send('send' . ucfirst($type), $data);
    }

    /**
     * Publish a message to a channel and return success or failure message in markdown format
     *
     * @param Message     $message
     * @param string|int  $channel_id
     * @param string|null $caption
     *
     * @return string
     * @throws TelegramException
     */
    protected function publish(Message $message, $channel_id, $caption = null)
    {
        $res = $this->sendBack($message, [
            'chat_id' => $channel_id,
            'caption' => $caption,
        ]);

        if ($res->isOk()) {
            /** @var Chat $channel */
            $channel = $res->result->getChat();
            $escaped_username = $channel->username ?
                $this->message->escapeMarkdown($channel->username) :
                '';

            $response = sprintf(
                'Message successfully sent to *%s*%s',
                filter_var($channel->title, FILTER_SANITIZE_SPECIAL_CHARS),
                $escaped_username ? " (@{$escaped_username})" : ''
            );
        } else {
            $escaped_username = $this->message->escapeMarkdown($channel_id);
            $response         = "Message not sent to *{$escaped_username}*" . PHP_EOL .
                                '- Does the channel exist?' . PHP_EOL .
                                '- Is the bot an admin of the channel?';
        }

        return $response;
    }
}
