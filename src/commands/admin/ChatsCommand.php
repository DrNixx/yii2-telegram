<?php
namespace onix\telegram\commands\admin;

use onix\telegram\commands\AdminCommand;
use onix\telegram\entities\Chat;
use onix\telegram\entities\ServerResponse;
use onix\telegram\exceptions\TelegramException;
use onix\telegram\Storage;
use yii\base\Exception as BaseException;

class ChatsCommand extends AdminCommand
{
    /**
     * @var string
     */
    protected $name = 'chats';

    /**
     * @var string
     */
    protected $description = 'List or search all chats stored by the bot';

    /**
     * @var string
     */
    protected $usage = '/chats, /chats * or /chats <search string>';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * Command execute method
     *
     * @return ServerResponse
     *
     * @throws TelegramException
     * @throws BaseException
     */
    public function execute()
    {
        $message = $this->message;

        $chat_id = $message->chat->id;
        $text = trim($message->getMessageText(true));

        $results = Storage::chatSearch([
            'groups' => true,
            'supergroups' => true,
            'channels' => true,
            'users' => true,
            'text' => ($text === '' || $text === '*') ? null : $text //Text to search in user/group name
        ]);

        $user_chats       = 0;
        $group_chats      = 0;
        $supergroup_chats = 0;
        $channel_chats    = 0;

        if ($text === '') {
            $text_back = '';
        } elseif ($text === '*') {
            $text_back = 'List of all bot chats:' . PHP_EOL;
        } else {
            $text_back = 'Chat search results:' . PHP_EOL;
        }

        if (is_array($results)) {
            foreach ($results as $result) {
                //Initialize a chat object
                $chat = new Chat($result->attributes);

                $whois = $chat->id;
                if ($this->telegram->getCommandObject('whois')) {
                    // We can't use '-' in command because part of it will become unclickable
                    $whois = '/whois' . str_replace('-', 'g', $chat->id);
                }

                if ($chat->isPrivateChat()) {
                    if ($text !== '') {
                        $text_back .= '- P ' . $chat->tryMention() . ' [' . $whois . ']' . PHP_EOL;
                    }

                    ++$user_chats;
                } elseif ($chat->isSuperGroup()) {
                    if ($text !== '') {
                        $text_back .= '- S ' . $chat->title . ' [' . $whois . ']' . PHP_EOL;
                    }

                    ++$supergroup_chats;
                } elseif ($chat->isGroupChat()) {
                    if ($text !== '') {
                        $text_back .= '- G ' . $chat->title . ' [' . $whois . ']' . PHP_EOL;
                    }

                    ++$group_chats;
                } elseif ($chat->isChannel()) {
                    if ($text !== '') {
                        $text_back .= '- C ' . $chat->title . ' [' . $whois . ']' . PHP_EOL;
                    }

                    ++$channel_chats;
                }
            }
        }

        if (($user_chats + $group_chats + $supergroup_chats) === 0) {
            $text_back = 'No chats found..';
        } else {
            $text_back .= "\nPrivate Chats: {$user_chats}";
            $text_back .= "\nGroups: {$group_chats}";
            $text_back .= "\nSuper Groups: {$supergroup_chats}";
            $text_back .= "\nChannels: {$channel_chats}";
            $text_back .= "\nTotal: " . ($user_chats + $group_chats + $supergroup_chats);

            if ($text === '') {
                $text_back .= "\n\nList all chats: /{$this->name} *\nSearch for chats: /{$this->name} <search string>";
            }
        }

        $data = [
            'chat_id' => $chat_id,
            'text' => $text_back,
        ];

        return $this->request->sendMessage($data);
    }
}
