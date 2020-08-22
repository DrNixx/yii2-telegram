<?php
namespace onix\telegram\commands\admin;

use onix\telegram\commands\AdminCommand;
use onix\telegram\entities\Chat;
use onix\telegram\entities\ServerResponse;
use onix\telegram\entities\UserProfilePhotos;
use onix\telegram\exceptions\TelegramException;
use onix\telegram\Storage;
use onix\telegram\models\Chat as ChatRepo;
use yii\base\Exception as BaseException;

/**
 * Admin "/whois" command
 */
class WhoisCommand extends AdminCommand
{
    /**
     * @var string
     */
    protected $name = 'whois';

    /**
     * @var string
     */
    protected $description = 'Lookup user or group info';

    /**
     * @var string
     */
    protected $usage = '/whois <id> or /whois <search string>';

    /**
     * @var string
     */
    protected $version = '1.3.0';

    /**
     * Command execute method
     *
     * @return ServerResponse
     * @throws TelegramException
     * @throws BaseException
     */
    public function execute()
    {
        $message = $this->message;

        $chat_id = $message->chat->id;
        $command = $message->command;
        $text    = trim($message->getMessageText(true));

        $data = ['chat_id' => $chat_id];

        //No point in replying to messages in private chats
        if (!$message->chat->isPrivateChat()) {
            $data['reply_to_message_id'] = $message->messageId;
        }

        if ($command !== 'whois') {
            $text = substr($command, 5);

            //We need that '-' now, bring it back
            if (strpos($text, 'g') === 0) {
                $text = str_replace('g', '-', $text);
            }
        }

        if ($text === '') {
            $text = 'Provide the id to lookup: /whois <id>';
        } else {
            $user_id    = $text;
            $chat       = null;
            $created_at = null;
            $updated_at = null;
            $result     = null;

            if (is_numeric($text)) {
                $results = Storage::chatSearch([
                    'groups' => true,
                    'supergroups' => true,
                    'channels' => true,
                    'users' => true,
                    //Specific chat_id to select
                    'chat_id' => $user_id,
                ]);

                if ($results !== false) {
                    $result = reset($results);
                }
            } else {
                $results = Storage::chatSearch([
                    'groups' => true,
                    'supergroups' => true,
                    'channels' => true,
                    'users' => true,
                    'text' => $text //Text to search in user/group name
                ]);

                if (is_array($results) && count($results) === 1) {
                    $result = reset($results);
                }
            }

            if ($result instanceof ChatRepo) {
                $chat = new Chat($result->attributes);

                $user_id    = $result->id;
                $created_at = $result->created_at;
                $updated_at = $result->updated_at;
                $old_id     = $result->old_id;
            }

            if ($chat !== null) {
                if ($chat->isPrivateChat()) {
                    $text = 'User ID: ' . $user_id . PHP_EOL;
                    $text .= 'Name: ' . $chat->firstName . ' ' . $chat->lastName . PHP_EOL;

                    $username = $chat->username;
                    if ($username !== null && $username !== '') {
                        $text .= 'Username: @' . $username . PHP_EOL;
                    }

                    $text .= 'First time seen: ' . $created_at . PHP_EOL;
                    $text .= 'Last activity: ' . $updated_at . PHP_EOL;

                    //Code from Whoami command
                    $limit    = 10;
                    $offset   = null;
                    $response = $this->request->getUserProfilePhotos(
                        [
                            'user_id' => $user_id,
                            'limit' => $limit,
                            'offset' => $offset,
                        ]
                    );

                    if ($response->isOk()) {
                        /** @var UserProfilePhotos $user_profile_photos */
                        $user_profile_photos = $response->result;

                        if ($user_profile_photos->totalCount > 0) {
                            $photos = $user_profile_photos->photoSets;

                            $photo   = $photos[0][2];
                            $file_id = $photo->fileId;

                            $data['photo']   = $file_id;
                            $data['caption'] = $text;

                            return $this->request->sendPhoto($data);
                        }
                    }
                } elseif ($chat->isGroupChat()) {
                    $text = 'Chat ID: '.$user_id . (!empty($old_id) ? ' (previously: ' . $old_id . ')' : '') . PHP_EOL;
                    $text .= 'Type: ' . ucfirst($chat->type) . PHP_EOL;
                    $text .= 'Title: ' . $chat->title . PHP_EOL;
                    $text .= 'First time added to group: ' . $created_at . PHP_EOL;
                    $text .= 'Last activity: ' . $updated_at . PHP_EOL;
                }
            } elseif (is_array($results) && count($results) > 1) {
                $text = 'Multiple chats matched!';
            } else {
                $text = 'Chat not found!';
            }
        }

        $data['text'] = $text;

        return $this->request->sendMessage($data);
    }
}
