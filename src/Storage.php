<?php
namespace onix\telegram;

use MongoDB\BSON\UTCDateTime;
use onix\telegram\entities\CallbackQuery;
use onix\telegram\entities\Chat;
use onix\telegram\entities\chatBoost\ChatBoostRemoved;
use onix\telegram\entities\chatBoost\ChatBoostUpdated as ChatBoostUpdated;
use onix\telegram\entities\ChatJoinRequest;
use onix\telegram\entities\ChatMemberUpdated;
use onix\telegram\entities\ChosenInlineResult;
use onix\telegram\entities\EditedChannelPost;
use onix\telegram\entities\EditedMessage;
use onix\telegram\entities\Entity;
use onix\telegram\entities\InlineQuery;
use onix\telegram\entities\Message;
use onix\telegram\entities\payments\PreCheckoutQuery;
use onix\telegram\entities\payments\ShippingQuery;
use onix\telegram\entities\Poll;
use onix\telegram\entities\PollAnswer;
use onix\telegram\entities\reaction\MessageReactionCountUpdated;
use onix\telegram\entities\reaction\MessageReactionUpdated;
use onix\telegram\entities\Update;
use onix\telegram\entities\User;
use onix\telegram\exceptions\TelegramException;
use onix\telegram\models\CallbackQuery as CallbackQueryRepo;
use onix\telegram\models\Chat as ChatRepo;
use onix\telegram\models\ChatBoostRemoved as ChatBoostRemovedRepo;
use onix\telegram\models\ChatBoostUpdated as ChatBoostUpdatedRepo;
use onix\telegram\models\ChatJoinRequest as ChatJoinRequestRepo;
use onix\telegram\models\ChatMemberUpdated as ChatMemberUpdatedRepo;
use onix\telegram\models\ChosenInlineResult as ChosenInlineResultRepo;
use onix\telegram\models\Conversation as ConversationRepo;
use onix\telegram\models\EditedMessage as EditedMessageRepo;
use onix\telegram\models\InlineQuery as InlineQueryRepo;
use onix\telegram\models\Message as MessageRepo;
use onix\telegram\models\MessageReactionCountUpdated as MessageReactionCountUpdatedRepo;
use onix\telegram\models\MessageReactionUpdated as MessageReactionUpdatedRepo;
use onix\telegram\models\Poll as PollRepo;
use onix\telegram\models\PollAnswer as PollAnswerRepo;
use onix\telegram\models\PreCheckoutQuery as PreCheckoutQueryRepo;
use onix\telegram\models\RequestLimiter;
use onix\telegram\models\ShippingQuery as ShippingQueryRepo;
use onix\telegram\models\TelegramUpdate as TelegramUpdateRepo;
use onix\telegram\models\User as TelegramUserRepo;
use onix\telegram\models\UserChat as UserChatRepo;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\mongodb\Exception as MongoDbException;

class Storage
{
    /**
     * Telegram class object
     *
     * @var Telegram
     */
    protected static Telegram $telegram;

    /**
     * Fetch message(s) from DB
     *
     * @param int|null $limit Limit the number of messages to fetch
     *
     * @return MessageRepo[] Fetched data or false if not connected
     */
    public static function selectMessages(?int $limit = null): array
    {
        $query = MessageRepo::find()->orderBy(['id' => SORT_DESC]);
        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->all();
    }

    protected static function getRepoError(string $msg, BaseActiveRecord $repo): string
    {
        $result = $msg;
        if (YII_DEBUG) {
            $result .= ": " . print_r($repo->errors, true);
        }

        return $result;
    }

    /**
     * Convert from unix timestamp to timestamp
     *
     * @param int|null $time Unix timestamp (if empty, current timestamp is used)
     *
     * @return UTCDateTime|null
     */
    protected static function getTimestamp(?int $time = null): ?UTCDateTime
    {
        return $time ? new UTCDateTime($time * 1000) : null;
    }

    /**
     * Convert array of Entity items to a JSON array
     *
     * @param Entity|null $entity
     * @param mixed|null $default
     *
     * @return mixed
     */
    public static function entityToJson(?Entity $entity, mixed $default = null): mixed
    {
        if ($entity === null) {
            return $default;
        }

        return Json::encode($entity);
    }

    /**
     * Convert array of Entity items to a JSON array
     *
     * @param Entity[]|null $entities
     * @param mixed|null $default
     *
     * @return mixed
     */
    public static function entitiesArrayToJson(?array $entities, mixed $default = null): mixed
    {
        if (($entities === null) && !is_array($entities)) {
            return $default;
        }

        return Json::encode($entities);
    }

    //<editor-fold desc="*** Conversation ***">

    /**
     * @param int $user_id
     * @param int $chat_id
     *
     * @return ConversationRepo|null
     */
    public static function conversationSelect(int $user_id, int $chat_id): ?ConversationRepo
    {
        //Select an active conversation
        return ConversationRepo::findOne([
            'status' => 'active',
            'userId' => $user_id,
            'chatId' => $chat_id
        ]);
    }

    /**
     * @param int $user_id
     * @param int $chat_id
     * @param string $command
     *
     * @return bool
     *
     * @throws \Exception
     */
    public static function conversationInsert(int $user_id, int $chat_id, string $command): bool
    {
        $repo = new ConversationRepo([
            'userId' => $user_id,
            'chatId' => $chat_id,
            'status' => 'active',
            'command' => $command,
            'notes' => '[]'
        ]);

        if (!$repo->insert()) {
            \Yii::warning(['Insert conversation error', $repo->errors], 'telegram');
            throw new TelegramException(self::getRepoError('Insert conversation error', $repo));
        }

        return true;
    }
    //</editor-fold>

    //<editor-fold desc="*** TelegramUpdate ***">
    /**
     * Fetch update(s) from DB
     *
     * @param int|null $id Check for unique update id
     *
     * @return TelegramUpdateRepo|null Fetched data or false if not connected
     */
    public static function telegramUpdateSelect(int $id = null): ?TelegramUpdateRepo
    {
        $query = TelegramUpdateRepo::find();
        if ($id !== null) {
            $query->andWhere(['_id' => $id]);
        } else {
            $query->orderBy(['_id' => SORT_DESC]);
        }

        return $query->one();
    }

    /**
     * Insert entry to telegram_update table
     *
     * @param int $update_id
     * @param int|null $chat_id
     * @param int|null $message_id
     * @param object|null $edited_message_id
     * @param int|null $channel_post_id
     * @param object|null $edited_channel_post_id
     * @param string|null $inline_query_id
     * @param string|null $chosen_inline_result_id
     * @param string|null $callback_query_id
     * @param string|null $shipping_query_id
     * @param string|null $pre_checkout_query_id
     * @param string|null $poll_id
     * @param object|null $poll_answer_poll_id
     * @param object|null $my_chat_member_updated_id
     * @param object|null $chat_member_updated_id
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     */
    protected static function telegramUpdateInsert(TelegramUpdateRepo $model): bool
    {
        if (count($model->dirtyAttributes) <= 1) {
            throw new TelegramException('All update fields is null');
        }

        $result = $model->save();
        if (!$result) {
            \Yii::warning(['Insert updates error', $model], 'telegram');
            if (YII_DEBUG) {
                throw new TelegramException(self::getRepoError('Insert updates error', $model));
            }
        }

        return $result;
    }
    //</editor-fold>


    /**
     * @param ChatMemberUpdated $entity
     *
     * @return object|null
     *
     * @throws TelegramException
     */
    private static function chatMemberUpdatedInsert(ChatMemberUpdated $entity): ?object
    {
        $chat = $entity->chat;
        self::chatInsert($chat);

        $user = $entity->from;
        self::userUpsert($user);

        $repo = new ChatMemberUpdatedRepo();
        $repo->assign($entity);
        $repo->chatId = $entity->chat->id;
        $repo->userId = $entity->from->id;
        $repo->date = self::getTimestamp($entity->date);

        $result = $repo->save();
        if (!$result) {
            \Yii::warning(['Insert updates error', $repo], 'telegram');
            return null;
        }

        return $repo->_id;
    }

    /**
     * @param ChatBoostUpdated $entity
     * @return object|null
     * @throws TelegramException
     */
    private static function chatBoostUpdatedRequestInsert(ChatBoostUpdated $entity): ?object
    {
        $chat = $entity->chat;
        self::chatInsert($chat);

        $repo = new ChatBoostUpdatedRepo();
        $repo->assign($entity);
        $repo->chatId = $entity->chat->id;

        $result = $repo->save();
        if (!$result) {
            \Yii::warning(['Insert updates error', $repo], 'telegram');
            return null;
        }

        return $repo->_id;
    }

    /**
     * @param ChatBoostUpdated $entity
     * @return object|null
     * @throws TelegramException
     */
    private static function chatBoostRemovedRequestInsert(ChatBoostRemoved $entity): ?object
    {
        $chat = $entity->chat;
        self::chatInsert($chat);

        $repo = new ChatBoostRemovedRepo();
        $repo->assign($entity);
        $repo->chatId = $entity->chat->id;
        $repo->removeDate = self::getTimestamp($entity->removeDate);

        $result = $repo->save();
        if (!$result) {
            \Yii::warning(['Insert updates error', $repo], 'telegram');
            return null;
        }

        return $repo->_id;
    }

    /**
     * @param MessageReactionUpdated $entity
     * @return object|null
     * @throws TelegramException
     */
    private static function messageReactionUpdatedRequestInsert(MessageReactionUpdated $entity): ?object
    {
        $chat = $entity->chat;
        self::chatInsert($chat);

        $user = $entity->user;
        if ($user) {
            self::userUpsert($user);
        }

        $actorChat = $entity->actorChat;
        if ($actorChat) {
            self::chatInsert($actorChat);
        }


        $repo = new MessageReactionUpdatedRepo();
        $repo->assign($entity);
        $repo->chatId = $chat->id;
        $repo->userId = $user?->id;
        $repo->actorChatId = $actorChat?->id;
        $repo->date = self::getTimestamp($entity->date);

        $result = $repo->save();
        if (!$result) {
            \Yii::warning(['Insert updates error', $repo], 'telegram');
            return null;
        }

        return $repo->_id;
    }

    private static function messageReactionCountUpdatedRequestInsert(MessageReactionCountUpdated $entity)
    {
        $chat = $entity->chat;
        self::chatInsert($chat);

        $repo = new MessageReactionCountUpdatedRepo();
        $repo->assign($entity);
        $repo->chatId = $chat->id;
        $repo->date = self::getTimestamp($entity->date);

        $result = $repo->save();
        if (!$result) {
            \Yii::warning(['Insert updates error', $repo], 'telegram');
            return null;
        }

        return $repo->_id;
    }

    /**
     * @param ChatJoinRequest $entity
     *
     * @return object|null
     *
     * @throws TelegramException
     */
    private static function chatJoinRequestInsert(ChatJoinRequest $entity): ?object
    {
        $chat = $entity->chat;
        self::chatInsert($chat);

        $user = $entity->from;
        self::userUpsert($user);

        $repo = new ChatJoinRequestRepo();
        $repo->assign($entity);
        $repo->chatId = $entity->chat->id;
        $repo->userId = $entity->from->id;
        $repo->date = self::getTimestamp($entity->date);

        $result = $repo->save();
        if (!$result) {
            \Yii::warning(['Insert updates error', $repo], 'telegram');
            return null;
        }

        return $repo->_id;
    }

    //<editor-fold desc="*** User ***">
    /**
     * Insert users and save their connection to chats
     *
     * @param User $user
     * @param Chat|null $chat
     *
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     */
    public static function userUpsert(User $user, Chat $chat = null): bool
    {
        $repo = TelegramUserRepo::findOne(['_id' => $user->id]);
        if ($repo === null) {
            $repo = new TelegramUserRepo(['_id' => $user->id]);
        }

        $repo->assign($user);

        if (!$repo->save()) {
            \Yii::warning(['User save error', $repo->errors], 'telegram');
            throw new TelegramException(self::getRepoError('User save error', $repo));
        }

        // Also insert the relationship to the chat into the user_chat table
        if ($chat) {
            $userChatRepo = UserChatRepo::findOne(['userId' => $user->id, 'chatId' => $chat->id]);
            if ($userChatRepo === null) {
                $userChatRepo = new UserChatRepo([
                    'userId' => $user->id,
                    'chatId' => $chat->id
                ]);

                if (!$userChatRepo->save()) {
                    \Yii::warning(['User chat save error', $userChatRepo->errors], 'telegram');
                    throw new TelegramException(self::getRepoError('User chat save error', $userChatRepo));
                }
            }
        }

        return true;
    }
    //</editor-fold>

    //<editor-fold desc="*** Chat ***">
    /**
     * Select Groups, Supergroups, Channels and/or single user Chats (also by ID or text)
     *
     * @param $select_chats_params
     *
     * @return ChatRepo[]|bool
     */
    public static function chatSearch($select_chats_params): array|bool
    {
        // Set defaults for omitted values.
        $select = ArrayHelper::merge([
            'groups' => true,
            'supergroups' => true,
            'channels' => true,
            'users' => true,
            'date_from' => null,
            'date_to' => null,
            'chat_id' => null,
            'text' => null,
            'language' => null,
        ], $select_chats_params);

        if (!$select['groups'] && !$select['users'] && !$select['supergroups'] && !$select['channels']) {
            return false;
        }

        $query = ChatRepo::find('chat');
        if ($select['users']) {
            $query->leftJoin(['user' => TelegramUserRepo::tableName()], 'user.id = chat.id');
        }

        $chat_or_user = [];

        if ($select['groups']) {
            $chat_or_user[] = ['chat.type' => 'group'];
        }

        if ($select['supergroups']) {
            $chat_or_user[] = ['chat.type' => 'supergroup'];
        }

        if ($select['channels']) {
            $chat_or_user[] = ['chat.type' => 'channel'];
        }

        if ($select['users']) {
            $chat_or_user[] = ['chat.type' => 'private'];
        }

        if (count($chat_or_user) > 0) {
            if (count($chat_or_user) > 1) {
                $query->andWhere(ArrayHelper::merge(['or'], $chat_or_user));
            } else {
                $query->andWhere($chat_or_user);
            }
        }

        if ($select['date_from'] !== null) {
            $query->andWhere(['=>', 'chat.updated_at', $select['date_from']]);
        }

        if ($select['date_to'] !== null) {
            $query->andWhere(['<=>', 'chat.updated_at', $select['date_to']]);
        }

        if ($select['chat_id'] !== null) {
            $query->andWhere(['chat.id' => $select['chat_id']]);
        }

        if ($select['text'] !== null) {
            $text_like = strtolower($select['text']);
            if ($select['users']) {
                $query->andWhere([
                    'or',
                    ['ilike', 'chat.title', $text_like],
                    ['ilike', 'user.first_name', $text_like],
                    ['ilike', 'user.last_name', $text_like],
                    ['ilike', 'user.username', $text_like],
                ]);
            } else {
                $query->andWhere(['ilike', 'chat.title', $text_like]);
            }
        }

        return $query->all();
    }

    /**
     * Insert chat
     *
     * @param Chat $chat
     * @param string|null $migrate_to_chat_id
     *
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     */
    public static function chatInsert(Chat $chat, ?string $migrate_to_chat_id = null): bool
    {
        $chat_id = $chat->id;
        $old_id = null;
        $chat_type = $chat->type;

        if ($migrate_to_chat_id !== null) {
            $chat_type = 'supergroup';
            $old_id = $chat_id;
            $chat_id = $migrate_to_chat_id;
        }

        $repo = ChatRepo::findOne(['_id' => $chat_id]);
        if ($repo === null) {
            $repo = new ChatRepo(['_id' => $chat_id]);
        }

        $repo->assign($chat);
        $repo->type = $chat_type;
        $repo->oldId = $old_id;

        if (!$repo->save()) {
            \Yii::warning(['Chat save error', $repo->errors], 'telegram');
            throw new TelegramException(self::getRepoError('User chat save error', $repo));
        }

        return true;
    }
    //</editor-fold>

    //<editor-fold desc="*** Update Request ***">
    /**
     * Insert request into database
     *
     * @param Update $update
     *
     * @return bool
     *
     * @throws TelegramException
     */
    public static function insertUpdateRequest(Update $update): bool
    {
        $repo = new TelegramUpdateRepo();
        $repo->_id = $update->updateId;

        if (($message = $update->message) && self::messageRequestInsert($message)) {
            $repo->chatId = $message->chat->id;
            $repo->messageId = $message->messageId;
        } elseif (($edited_message = $update->editedMessage) && self::editedMessageRequestInsert($edited_message)) {
            $repo->chatId = $edited_message->chat->id;
            $repo->editedMessageId = $edited_message->editedMessageId;
        } elseif (($channel_post = $update->channelPost) && self::messageRequestInsert($channel_post)) {
            $repo->chatId         = $channel_post->chat->id;
            $repo->channelPostId = $channel_post->messageId;
        } elseif (($edited_channel_post = $update->editedChannelPost)
            && self::editedMessageRequestInsert($edited_channel_post)
        ) {
            $repo->chatId = $edited_channel_post->chat->id;
            $repo->editedChannelPostId = $edited_channel_post->editedMessageId;
        } elseif (($inline_query = $update->inlineQuery) && self::inlineQueryRequestInsert($inline_query)) {
            $repo->inlineQueryId = $inline_query->id;
        } elseif (($chosen_inline_result = $update->chosenInlineResult) &&
            self::chosenInlineResultRequestInsert($chosen_inline_result)
        ) {
            $repo->chosenInlineResultId = $chosen_inline_result->resultId;
        } elseif (($callback_query = $update->callbackQuery)) {
            $repo->callbackQueryId = self::callbackQueryRequestInsert($callback_query);
        } elseif (($shipping_query = $update->shippingQuery) && self::shippingQueryRequestInsert($shipping_query)) {
            $repo->shippingQueryId = $shipping_query->id;
        } elseif (($pre_checkout_query = $update->preCheckoutQuery) &&
            self::preCheckoutQueryRequestInsert($pre_checkout_query)
        ) {
            $repo->preCheckoutQueryId = $pre_checkout_query->id;
        } elseif (($poll = $update->poll) && self::pollRequestInsert($poll)) {
            $repo->pollId = $poll->id;
        } elseif (($poll_answer = $update->pollAnswer)) {
            $repo->pollAnswerId = self::pollAnswerRequestInsert($poll_answer);
        } elseif ($my_chat_member = $update->myChatMember) {
            $repo->myChatMemberId = self::chatMemberUpdatedInsert($my_chat_member);
        } elseif ($chat_member = $update->chatMember) {
            $repo->chatMemberId = self::chatMemberUpdatedInsert($chat_member);
        } elseif ($chat_join_request = $update->chatJoinRequest) {
            $repo->chatJoinRequestId = self::chatJoinRequestInsert($chat_join_request);
        } elseif ($chat_boost_updated = $update->chatBoost) {
            $repo->chatBoostId = self::chatBoostUpdatedRequestInsert($chat_boost_updated);
        } elseif ($chat_boost_removed = $update->removedChatBoost) {
            $repo->removedChatBoostId = self::chatBoostRemovedRequestInsert($chat_boost_removed);
        } elseif ($data = $update->messageReaction) {
            $repo->messageReactionId = self::messageReactionUpdatedRequestInsert($data);
        } elseif ($data = $update->messageReactionCount) {
            $repo->messageReactionCountId = self::messageReactionCountUpdatedRequestInsert($data);
        } else {
            return false;
        }

        return self::telegramUpdateInsert($repo);
    }

    /**
     * Insert Message request in db
     *
     * @param Message $entity
     *
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     */
    public static function messageRequestInsert(Message $entity): bool
    {
        $date = self::getTimestamp($entity->date);

        // Insert chat, update chat id in case it migrated
        $chat = $entity->chat;
        self::chatInsert($chat, $entity->migrateToChatId);

        if ($senderChat = $entity->senderChat) {
            self::chatInsert($senderChat);
            $senderChat = $senderChat->id;
        }

        // Insert user and the relation with the chat
        if ($user = $entity->from) {
            self::userUpsert($user, $chat);
        }

        // Insert the forwarded message user in users table
        $forward_date = $entity->forwardDate ? self::getTimestamp($entity->forwardDate) : null;

        if ($forward_from = $entity->forwardFrom) {
            self::userUpsert($forward_from);
            $forward_from = $forward_from->id;
        }
        if ($forward_from_chat = $entity->forwardFromChat) {
            self::chatInsert($forward_from_chat);
            $forward_from_chat = $forward_from_chat->id;
        }

        $via_bot_id = null;
        if ($via_bot = $entity->viaBot) {
            self::userUpsert($via_bot);
            $via_bot_id = $via_bot->id;
        }

        // New and left chat member
        $new_chat_members_ids = null;
        $left_chat_member_id  = null;

        $new_chat_members = $entity->newChatMembers;
        $left_chat_member = $entity->leftChatMember;
        if (!empty($new_chat_members)) {
            foreach ($new_chat_members as $new_chat_member) {
                if ($new_chat_member instanceof User) {
                    // Insert the new chat user
                    self::userUpsert($new_chat_member, $chat);
                    $new_chat_members_ids[] = $new_chat_member->id;
                }
            }
        } elseif ($left_chat_member) {
            // Insert the left chat user
            self::userUpsert($left_chat_member, $chat);
            $left_chat_member_id = $left_chat_member->id;
        }

        $user_id = $user?->id;
        $chat_id = $chat->id;

        $reply_to_message_id = null;
        if ($reply_to_message = $entity->replyToMessage) {
            $reply_to_message_id = $reply_to_message->messageId;
            // please notice that, as explained in the documentation, reply_to_message don't contain other
            // reply_to_message field so recursion deep is 1
            self::messageRequestInsert($reply_to_message);
        }

        $reply_to_chat_id = null;
        if ($reply_to_message_id !== null) {
            $reply_to_chat_id = $reply_to_message->chat->id;
        }

        $repo = MessageRepo::findOne(['chatId' => $chat_id, 'id' => $entity->messageId]);
        if ($repo === null) {
            $repo = new MessageRepo(['chatId' => $chat_id, 'id' => $entity->messageId]);
        }

        $repo->assign($entity);
        $repo->userId = $user_id;
        $repo->date = $date;
        $repo->senderChatId = $senderChat;
        $repo->forwardFrom = $forward_from;
        $repo->forwardFromChat = $forward_from_chat;
        $repo->forwardDate = $forward_date;
        $repo->replyToChat = $reply_to_chat_id;
        $repo->replyToMessage = $reply_to_message_id;
        $repo->viaBot = $via_bot_id;
        $repo->editDate = self::getTimestamp($entity->editDate);
        $repo->newChatMembers = $new_chat_members_ids;
        $repo->leftChatMember = $left_chat_member_id;

        if (!$repo->save()) {
            \Yii::warning(['Message save error', $repo->errors], 'telegram');
            throw new TelegramException(self::getRepoError('Message save error', $repo));
        }

        return true;
    }

    /**
     * Insert Edited Message request in db
     *
     * @param EditedChannelPost|EditedMessage $entity
     *
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     * @throws \Exception
     */
    public static function editedMessageRequestInsert(EditedMessage|EditedChannelPost $entity): bool
    {
        $date = self::getTimestamp($entity->date);
        $edit_date = self::getTimestamp($entity->editDate);

        // Insert chat
        $chat = $entity->chat;
        self::chatInsert($chat);

        // Insert user and the relation with the chat
        if ($user = $entity->from) {
            self::userUpsert($user, $chat);
        }

        $user_id = $user?->id;

        $repo = new EditedMessageRepo();
        $repo->assign($entity);
        $repo->chatId = $chat->id;
        $repo->userId = $user_id;
        $repo->date = $date;
        $repo->editDate = $edit_date ?? $date;

        if (!$repo->insert()) {
            \Yii::warning(['Edited Message save error', $repo->errors], 'telegram');
            throw new TelegramException(self::getRepoError('Edited Message save error', $repo));
        }

        $entity->editedMessageId = $repo->_id;

        return true;
    }

    /**
     * Insert inline query request into database
     *
     * @param InlineQuery $entity
     *
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     */
    public static function inlineQueryRequestInsert(InlineQuery $entity): bool
    {
        $user_id = null;

        if ($user = $entity->from) {
            $user_id = $user->id;
            self::userUpsert($user);
        }

        $repo = InlineQueryRepo::findOne(['_id' => $entity->id]);
        if ($repo === null) {
            $repo = new InlineQueryRepo(['_id' => $entity->id]);
        }

        $repo->assign($entity);
        $repo->userId = $user_id;


        if (!$repo->save()) {
            \Yii::warning(['Inline query save error', $repo->errors], 'telegram');
            throw new TelegramException(self::getRepoError('Inline query save error', $repo));
        }

        return true;
    }

    /**
     * Insert chosen inline result request into database
     *
     * @param ChosenInlineResult $entity
     *
     * @return string ID if the insert was successful
     *
     * @throws TelegramException
     */
    public static function chosenInlineResultRequestInsert(ChosenInlineResult $entity): string
    {
        $user_id = null;

        if ($user = $entity->from) {
            $user_id = $user->id;
            self::userUpsert($user);
        }

        $repo = new ChosenInlineResultRepo();
        $repo->assign($entity);
        $repo->_id = $entity->resultId;
        $repo->userId = $user_id;

        if (!$repo->save()) {
            \Yii::warning(['Inline result save error', $repo->errors], 'telegram');
            throw new TelegramException(self::getRepoError('Inline result save error', $repo));
        }

        return $repo->_id;
    }

    /**
     * Insert callback query request into database
     *
     * @param CallbackQuery $entity
     *
     * @return string ID if the insert was successful
     *
     * @throws TelegramException
     */
    public static function callbackQueryRequestInsert(CallbackQuery $entity): string
    {
        $repo = CallbackQueryRepo::findOne(['_id' => $entity->id]);
        if ($repo === null) {
            $repo = new CallbackQueryRepo(['_id' => $entity->id]);
        }

        $user_id = null;

        if ($user = $entity->from) {
            $user_id = $user->id;
            self::userUpsert($user);
        }

        $chat_id    = null;
        $message_id = null;
        if ($message = $entity->message) {
            $chat_id = $message->chat->id;
            $message_id = $message->messageId;

            $is_message = MessageRepo::find()->where(['chatId' => $chat_id, 'id' => $message_id])->exists();
            if ($is_message) {
                self::editedMessageRequestInsert($message);
            } else {
                self::messageRequestInsert($message);
            }
        }

        $repo->assign($entity);
        $repo->userId = $user_id;
        $repo->chatId = $chat_id;
        $repo->messageId = $message_id;

        if (!$repo->save()) {
            \Yii::warning(['Callback query save error', $repo->errors], 'telegram');
            throw new TelegramException(self::getRepoError('Callback query save error', $repo));
        }

        return $repo->_id;
    }

    /**
     * Insert shipping query request into database
     *
     * @param ShippingQuery $entity
     *
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     */
    public static function shippingQueryRequestInsert(ShippingQuery $entity): bool
    {
        $repo = ShippingQueryRepo::findOne(['_id' => $entity->id]);
        if ($repo === null) {
            $repo = new ShippingQueryRepo(['_id' => $entity->id]);
        }

        $user_id = null;
        if ($user = $entity->from) {
            $user_id = $user->id;
            self::userUpsert($user);
        }

        $repo->assign($entity);
        $repo->userId = $user_id;

        if (!$repo->save()) {
            \Yii::warning(['Shipping query save error', $repo->errors], 'telegram');
            throw new TelegramException(self::getRepoError('Shipping query save error', $repo));
        }

        return true;
    }

    /**
     * Insert pre checkout query request into database
     *
     * @param PreCheckoutQuery $entity
     *
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     */
    public static function preCheckoutQueryRequestInsert(PreCheckoutQuery $entity): bool
    {
        $repo = PreCheckoutQueryRepo::findOne(['_id' => $entity->id]);
        if ($repo === null) {
            $repo = new PreCheckoutQueryRepo(['_id' => $entity->id]);
        }

        $user_id = null;
        if ($user = $entity->from) {
            $user_id = $user->id;
            self::userUpsert($user);
        }

        $repo->assign($entity);
        $repo->userId = $user_id;

        if (!$repo->save()) {
            \Yii::warning(['PreCheckout query save error', $repo->errors], 'telegram');
            throw new TelegramException(self::getRepoError('PreCheckout query save error', $repo));
        }

        return true;
    }

    /**
     * Insert poll request into database
     *
     * @param Poll $entity
     *
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     */
    public static function pollRequestInsert(Poll $entity): bool
    {
        $repo = PollRepo::findOne(['_id' => $entity->id]);
        if ($repo === null) {
            $repo = new PollRepo(['_id' => $entity->id]);
        }

        $repo->assign($entity);
        $repo->closeDate = self::getTimestamp($entity->closeDate);

        if (!$repo->save()) {
            \Yii::warning(['Poll save error', $repo->errors], 'telegram');
            throw new TelegramException(self::getRepoError('Poll save error', $repo));
        }

        return true;
    }

    /**
     * Insert poll answer request into database
     *
     * @param PollAnswer $poll_answer
     *
     * @return object ID if the insert was successful
     *
     * @throws TelegramException
     */
    public static function pollAnswerRequestInsert(PollAnswer $poll_answer): object
    {
        $user_id = null;
        if ($user = $poll_answer->user) {
            $user_id = $user->id;
            self::userUpsert($user);
        }

        $repo = PollAnswerRepo::findOne(['pollId' => $poll_answer->pollId, 'userId' => $user_id]);
        if ($repo === null) {
            $repo = new PollAnswerRepo(['pollId' => $poll_answer->pollId, 'userId' => $user_id]);
        }

        $repo->assign($poll_answer);

        if (!$repo->save()) {
            \Yii::warning(['Poll answer save error', $repo->errors], 'telegram');
            throw new TelegramException(self::getRepoError('Poll answer save error', $repo));
        }

        return $repo->_id;
    }
    //</editor-fold>

    //<editor-fold desc="*** Telegram Request ***">
    /**
     * Insert Telegram API request in db
     *
     * @param string $method
     * @param array $data
     *
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     */
    public static function insertTelegramRequest(string $method, array $data): bool
    {
        $chat_id = $data['chat_id'] ?? null;
        $inline_message_id = $data['inline_message_id'] ?? null;

        $limiter = new RequestLimiter();
        $limiter->chatId = $chat_id;
        $limiter->inlineMessageId = $inline_message_id;
        $limiter->method = $method;

        if (!$limiter->save()) {
            \Yii::warning(['Request limiter save error', $limiter->errors], 'telegram');
            throw new TelegramException(self::getRepoError('Request limiter save error', $limiter));
        }

        return true;
    }

    /**
     * Get Telegram API request count for current chat / message
     *
     * @param int|null $chat_id
     * @param string|null $inline_message_id
     *
     * @return array Array containing TOTAL and CURRENT fields or false on invalid arguments
     * @throws MongoDbException
     */
    public static function getTelegramRequestCount(int $chat_id = null, string $inline_message_id = null): array
    {
        $date = new UTCDateTime();
        $date_minute = new UTCDateTime($date->toDateTime()->modify('-1 minute'));

        return [
            'LIMIT_PER_SEC_ALL' => count(RequestLimiter::find()
                ->where(['>=', 'date', $date])
                ->select(['chatId'])
                ->distinct('chatId')),
            'LIMIT_PER_SEC' => RequestLimiter::find()
                ->where(['>=', 'date', $date_minute])
                ->andWhere([
                    'or',
                    ['chatId' => $chat_id, 'inlineMessageId' => null],
                    ['chatId' => null, 'inlineMessageId' => $inline_message_id],
                ])
                ->count(),
            'LIMIT_PER_MINUTE' => RequestLimiter::find()
                ->where(['>=', 'date', $date_minute])
                ->andWhere(['chatId' => $chat_id])
                ->count()
        ];
    }
    //</editor-fold>
}
