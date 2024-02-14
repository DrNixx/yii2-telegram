<?php
/** @noinspection PhpIllegalPsrClassPathInspection */

namespace onix\telegram\migrations;

use onix\telegram\migrations\models_v1\Chat as ChatOld;
use onix\telegram\migrations\models_v1\User as UserOld;
use onix\telegram\migrations\models_v1\UserChat as UserChatOld;
use onix\telegram\models\Chat;
use onix\telegram\models\Conversation;
use onix\telegram\models\Message;
use onix\telegram\models\PollAnswer;
use onix\telegram\models\User;
use onix\telegram\models\UserChat;
use yii\db\Migration;

/**
 * Class for migration to MongoDB
 */
class MongoMigrate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Conversation::checkIndices();
        UserChat::checkIndices();
        Message::checkIndices();
        PollAnswer::checkIndices();

        UserChat::deleteAll();
        Chat::deleteAll();
        User::deleteAll();

        $users = UserOld::find()->all();
        foreach ($users as $oldUser) {
            $user = new User();
            $user->_id = $oldUser->id;
            $user->isBot = $oldUser->is_bot;
            $user->userId = $oldUser->user_id;
            $user->firstName = $oldUser->first_name;
            $user->lastName = $oldUser->last_name;
            $user->languageCode = $oldUser->language_code;

            $user->save();
        }

        $chats = ChatOld::find()->all();
        foreach ($chats as $oldChat) {
            $chat = new Chat();
            $chat->_id = $oldChat->id;
            $chat->type = $oldChat->type;
            $chat->title = $oldChat->title;
            $chat->firstName = $oldChat->first_name;
            $chat->lastName = $oldChat->last_name;
            $chat->username = $oldChat->username;
            $chat->allMembersAreAdministrators = $oldChat->all_members_are_administrators;
            $chat->oldId = $oldChat->old_id;

            $chat->save();
        }

        $userChats = UserChatOld::find()->all();
        foreach ($userChats as $old) {
            $userChat = new UserChat();
            $userChat->userId = $old->user_id;
            $userChat->chatId = $old->chat_id;
            $userChat->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
