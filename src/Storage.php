<?php
namespace onix\telegram;

use onix\telegram\entities\CallbackQuery;
use onix\telegram\entities\Chat;
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
use onix\telegram\entities\Update;
use onix\telegram\entities\User;
use onix\telegram\exceptions\TelegramException;
use onix\telegram\models\CallbackQuery as CallbackQueryRepo;
use onix\telegram\models\Chat as ChatRepo;
use onix\telegram\models\ChosenInlineResult as ChosenInlineResultRepo;
use onix\telegram\models\Conversation as ConversationRepo;
use onix\telegram\models\EditedMessage as EditedMessageRepo;
use onix\telegram\models\InlineQuery as InlineQueryRepo;
use onix\telegram\models\Message as MessageRepo;
use onix\telegram\models\Poll as PollRepo;
use onix\telegram\models\PollAnswer as PollAnswerRepo;
use onix\telegram\models\PreCheckoutQuery as PreCheckoutQueryRepo;
use onix\telegram\models\RequestLimiter;
use onix\telegram\models\ShippingQuery as ShippingQueryRepo;
use onix\telegram\models\TelegramUpdate as TelegramUpdateRepo;
use onix\telegram\models\User as TelegramUserRepo;
use onix\telegram\models\UserChat as UserChatRepo;
use Yii;
use yii\base\Exception as BaseException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class Storage
{
    /**
     * Telegram class object
     *
     * @var Telegram
     */
    protected static $telegram;

    /**
     * Fetch message(s) from DB
     *
     * @param int|null $limit Limit the number of messages to fetch
     *
     * @return MessageRepo[]|bool Fetched data or false if not connected
     */
    public static function selectMessages($limit = null)
    {
        $query = MessageRepo::find()->orderBy(['id' => SORT_DESC]);
        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->all();
    }

    /**
     * Convert from unix timestamp to timestamp
     *
     * @param int|null $time Unix timestamp (if empty, current timestamp is used)
     *
     * @return string
     */
    protected static function getTimestamp($time = null)
    {
        return date('Y-m-d H:i:s', $time ?: time());
    }

    /**
     * Convert array of Entity items to a JSON array
     *
     * @param Entity|null $entity
     * @param mixed $default
     *
     * @return mixed
     */
    public static function entityToJson($entity, $default = null)
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
     * @param mixed $default
     *
     * @return mixed
     */
    public static function entitiesArrayToJson($entities, $default = null)
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
    public static function conversationSelect($user_id, $chat_id)
    {
        //Select an active conversation
        return ConversationRepo::findOne([
            'status' => 'active',
            'user_id' => $user_id,
            'chat_id' => $chat_id
        ]);
    }

    /**
     * @param int $user_id
     * @param int $chat_id
     * @param string $command
     *
     * @return bool
     *
     * @throws BaseException
     */
    public static function conversationInsert($user_id, $chat_id, $command)
    {
        $conversation = new ConversationRepo([
            'user_id' => $user_id,
            'chat_id' => $chat_id,
            'status' => 'active',
            'command' => $command,
            'notes' => '[]'
        ]);

        if ($conversation->insert()) {
            return true;
        } else {
            Yii::warning(['Insert conversation error', $conversation->errors], 'telegram');
            throw new TelegramException();
        }
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
    public static function telegramUpdateSelect($id = null)
    {
        $query = TelegramUpdateRepo::find();
        if ($id !== null) {
            $query->andWhere(['id' => $id]);
        } else {
            $query->orderBy(['id' => SORT_DESC]);
        }

        return $query->one();
    }

    /**
     * Insert entry to telegram_update table
     *
     * @param string $update_id
     * @param string|null $chat_id
     * @param string|null $message_id
     * @param string|null $edited_message_id
     * @param string|null $channel_post_id
     * @param string|null $edited_channel_post_id
     * @param string|null $inline_query_id
     * @param string|null $chosen_inline_result_id
     * @param string|null $callback_query_id
     * @param string|null $shipping_query_id
     * @param string|null $pre_checkout_query_id
     * @param string|null $poll_id
     * @param string|null $poll_answer_poll_id
     *
     * @return bool If the insert was successful
     * @throws TelegramException
     * @throws BaseException
     */
    protected static function telegramUpdateInsert(
        $update_id,
        $chat_id = null,
        $message_id = null,
        $edited_message_id = null,
        $channel_post_id = null,
        $edited_channel_post_id = null,
        $inline_query_id = null,
        $chosen_inline_result_id = null,
        $callback_query_id = null,
        $shipping_query_id = null,
        $pre_checkout_query_id = null,
        $poll_id = null,
        $poll_answer_poll_id = null
    ) {
        if (($message_id === null) &&
            ($edited_message_id === null) &&
            ($channel_post_id === null) &&
            ($edited_channel_post_id === null) &&
            ($inline_query_id === null) &&
            ($chosen_inline_result_id === null) &&
            ($callback_query_id === null) &&
            ($shipping_query_id === null) &&
            ($pre_checkout_query_id === null) &&
            ($poll_id === null) &&
            ($poll_answer_poll_id === null)
        ) {
            throw new TelegramException('All update fields is null');
        }

        $data = [
            'id' => $update_id,
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'edited_message_id' => $edited_message_id,
            'channel_post_id' => $channel_post_id,
            'edited_channel_post_id' => $edited_channel_post_id,
            'inline_query_id' => $inline_query_id,
            'chosen_inline_result_id' => $chosen_inline_result_id,
            'callback_query_id' => $callback_query_id,
            'shipping_query_id' => $shipping_query_id,
            'pre_checkout_query_id' => $pre_checkout_query_id,
            'poll_id' => $poll_id,
            'poll_answer_poll_id' => $poll_answer_poll_id
        ];

        Yii::debug(['Try insert', $data], 'telegram');

        $update = new TelegramUpdateRepo([
            'id' => $update_id,
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'edited_message_id' => $edited_message_id,
            'channel_post_id' => $channel_post_id,
            'edited_channel_post_id' => $edited_channel_post_id,
            'inline_query_id' => $inline_query_id,
            'chosen_inline_result_id' => $chosen_inline_result_id,
            'callback_query_id' => $callback_query_id,
            'shipping_query_id' => $shipping_query_id,
            'pre_checkout_query_id' => $pre_checkout_query_id,
            'poll_id' => $poll_id,
            'poll_answer_poll_id' => $poll_answer_poll_id
        ]);

        $result = $update->save();
        if (!$result) {
            Yii::warning(['Insert updates error', $update], 'telegram');
        }

        return $result;
    }
    //</editor-fold>

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
     * @throws BaseException
     */
    public static function userUpsert(User $user, Chat $chat = null)
    {
        $userRepo = TelegramUserRepo::findOne($user->id);
        if ($userRepo === null) {
            $userRepo = new TelegramUserRepo(['id' => $user->id]);
        }

        $userRepo->is_bot = boolval($user->isBot);
        $userRepo->username = $user->username;
        $userRepo->first_name = $user->firstName;
        $userRepo->last_name = $user->lastName;
        $userRepo->language_code = $user->languageCode;

        if (!$userRepo->save()) {
            Yii::warning(['User save error', $userRepo->errors], 'telegram');
            throw new TelegramException('User save error');
        }

        // Also insert the relationship to the chat into the user_chat table
        if ($chat) {
            $userChatRepo = UserChatRepo::findOne(['user_id' => $user->id, 'chat_id' => $chat->id]);
            if ($userChatRepo === null) {
                $userChatRepo = new UserChatRepo([
                    'user_id' => $user->id,
                    'chat_id' => $chat->id
                ]);

                if (!$userChatRepo->save()) {
                    Yii::warning(['User chat save error', $userChatRepo->errors], 'telegram');
                    throw new TelegramException('User chat save error');
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
    public static function chatSearch($select_chats_params)
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
     * @throws BaseException
     */
    public static function chatInsert(Chat $chat, $migrate_to_chat_id = null)
    {
        $chat_id = $chat->id;
        $old_id = null;
        $chat_type = $chat->type;

        if ($migrate_to_chat_id !== null) {
            $chat_type = 'supergroup';
            $old_id = $chat_id;
            $chat_id = $migrate_to_chat_id;
        }

        $chatRepo = ChatRepo::findOne(['id' => $chat_id]);
        if ($chatRepo === null) {
            $chatRepo = new ChatRepo(['id' => $chat_id]);
        }

        $chatRepo->type = $chat_type;
        $chatRepo->title = $chat->title;
        $chatRepo->username = $chat->username;
        $chatRepo->first_name = $chat->firstName;
        $chatRepo->last_name = $chat->lastName;
        $chatRepo->all_members_are_administrators = boolval($chat->allMembersAreAdministrators);
        $chatRepo->old_id = $old_id;

        if (!$chatRepo->save()) {
            Yii::warning(['Chat save error', $chatRepo->errors], 'telegram');
            throw new TelegramException('User chat save error');
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
     * @throws BaseException
     * @throws TelegramException
     */
    public static function insertUpdateRequest(Update $update)
    {
        $chat_id                 = null;
        $message_id              = null;
        $edited_message_id       = null;
        $channel_post_id         = null;
        $edited_channel_post_id  = null;
        $inline_query_id         = null;
        $chosen_inline_result_id = null;
        $callback_query_id       = null;
        $shipping_query_id       = null;
        $pre_checkout_query_id   = null;
        $poll_id                 = null;
        $poll_answer_poll_id     = null;

        if (($message = $update->message) && self::messageRequestInsert($message)) {
            $chat_id = $message->chat->id;
            $message_id = $message->messageId;
        } elseif (($edited_message = $update->editedMessage) && self::editedMessageRequestInsert($edited_message)) {
            $chat_id = $edited_message->chat->id;
            $edited_message_id = $edited_message->edited_message_id;
        } elseif (($channel_post = $update->channelPost) && self::messageRequestInsert($channel_post)) {
            $chat_id         = $channel_post->chat->id;
            $channel_post_id = $channel_post->messageId;
        } elseif (($edited_channel_post = $update->editedChannelPost)
            && self::editedMessageRequestInsert($edited_channel_post)
        ) {
            $chat_id = $edited_channel_post->chat->id;
            $edited_channel_post_id = $edited_channel_post->edited_message_id;
        } elseif (($inline_query = $update->inlineQuery) && self::inlineQueryRequestInsert($inline_query)) {
            $inline_query_id = $inline_query->id;
        } elseif (($chosen_inline_result = $update->chosenInlineResult) &&
            self::chosenInlineResultRequestInsert($chosen_inline_result)
        ) {
            $chosen_inline_result_id = $chosen_inline_result->chosen_inline_result_id;
        } elseif (($callback_query = $update->callbackQuery) && self::callbackQueryRequestInsert($callback_query)) {
            $callback_query_id = $callback_query->id;
        } elseif (($shipping_query = $update->shippingQuery) && self::shippingQueryRequestInsert($shipping_query)) {
            $shipping_query_id = $shipping_query->id;
        } elseif (($pre_checkout_query = $update->preCheckoutQuery) &&
            self::preCheckoutQueryRequestInsert($pre_checkout_query)
        ) {
            $pre_checkout_query_id = $pre_checkout_query->id;
        } elseif (($poll = $update->poll) && self::pollRequestInsert($poll)) {
            $poll_id = $poll->id;
        } elseif (($poll_answer = $update->pollAnswer) && self::pollAnswerRequestInsert($poll_answer)) {
            $poll_answer_poll_id = $poll_answer->pollId;
        } else {
            return false;
        }

        return self::telegramUpdateInsert(
            $update->updateId,
            $chat_id,
            $message_id,
            $edited_message_id,
            $channel_post_id,
            $edited_channel_post_id,
            $inline_query_id,
            $chosen_inline_result_id,
            $callback_query_id,
            $shipping_query_id,
            $pre_checkout_query_id,
            $poll_id,
            $poll_answer_poll_id
        );
    }

    /**
     * Insert Message request in db
     *
     * @param Message $message
     *
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     * @throws BaseException
     */
    public static function messageRequestInsert(Message $message)
    {
        $date = self::getTimestamp($message->date);

        // Insert chat, update chat id in case it migrated
        $chat = $message->chat;
        self::chatInsert($chat, $message->migrateToChatId);

        // Insert user and the relation with the chat
        if ($user = $message->from) {
            self::userUpsert($user, $chat);
        }

        // Insert the forwarded message user in users table
        $forward_date = $message->forwardDate ? self::getTimestamp($message->forwardDate) : null;

        if ($forward_from = $message->forwardFrom) {
            self::userUpsert($forward_from);
            $forward_from = $forward_from->id;
        }
        if ($forward_from_chat = $message->forwardFromChat) {
            self::chatInsert($forward_from_chat);
            $forward_from_chat = $forward_from_chat->id;
        }

        $via_bot_id = null;
        if ($via_bot = $message->viaBot) {
            self::userUpsert($via_bot);
            $via_bot_id = $via_bot->id;
        }

        // New and left chat member
        $new_chat_members_ids = null;
        $left_chat_member_id  = null;

        $new_chat_members = $message->newChatMembers;
        $left_chat_member = $message->leftChatMember;
        if (!empty($new_chat_members)) {
            foreach ($new_chat_members as $new_chat_member) {
                if ($new_chat_member instanceof User) {
                    // Insert the new chat user
                    self::userUpsert($new_chat_member, $chat);
                    $new_chat_members_ids[] = $new_chat_member->id;
                }
            }
            $new_chat_members_ids = implode(',', $new_chat_members_ids);
        } elseif ($left_chat_member) {
            // Insert the left chat user
            self::userUpsert($left_chat_member, $chat);
            $left_chat_member_id = $left_chat_member->id;
        }

        $user_id = $user ? $user->id : null;
        $chat_id = $chat->id;

        $reply_to_message_id = null;
        if ($reply_to_message = $message->replyToMessage) {
            $reply_to_message_id = $reply_to_message->messageId;
            // please notice that, as explained in the documentation, reply_to_message don't contain other
            // reply_to_message field so recursion deep is 1
            self::messageRequestInsert($reply_to_message);
        }

        $reply_to_chat_id = null;
        if ($reply_to_message_id !== null) {
            $reply_to_chat_id = $reply_to_message->chat->id;
        }

        $messageRepo = MessageRepo::findOne(['chat_id' => $chat_id, 'id' => $message->messageId]);
        if ($messageRepo === null) {
            $messageRepo = new MessageRepo(['chat_id' => $chat_id, 'id' => $message->messageId]);
        }

        $messageRepo->user_id = $user_id;
        $messageRepo->date = $date;
        $messageRepo->forward_from = $forward_from;
        $messageRepo->forward_from_chat = $forward_from_chat;
        $messageRepo->forward_from_message_id = $message->forwardFromMessageId;
        $messageRepo->forward_signature = $message->forwardSignature;
        $messageRepo->forward_sender_name = $message->forwardSenderName;
        $messageRepo->forward_date = $forward_date;
        $messageRepo->reply_to_chat = $reply_to_chat_id;
        $messageRepo->reply_to_message = $reply_to_message_id;
        $messageRepo->via_bot = $via_bot_id;
        $messageRepo->edit_date = self::getTimestamp($message->editDate);
        $messageRepo->media_group_id = $message->mediaGroupId;
        $messageRepo->author_signature = $message->authorSignature;
        $messageRepo->text = $message->text;
        $messageRepo->entities = self::entitiesArrayToJson($message->entities);
        $messageRepo->caption_entities = self::entitiesArrayToJson($message->captionEntities);
        $messageRepo->audio = self::entityToJson($message->audio);
        $messageRepo->document = self::entityToJson($message->document);
        $messageRepo->animation = self::entityToJson($message->animation);
        $messageRepo->game = self::entityToJson($message->game);
        $messageRepo->photo = self::entitiesArrayToJson($message->photo);
        $messageRepo->sticker = self::entityToJson($message->sticker);
        $messageRepo->video = self::entityToJson($message->video);
        $messageRepo->voice = self::entityToJson($message->voice);
        $messageRepo->video_note = self::entityToJson($message->videoNote);
        $messageRepo->caption = $message->caption;
        $messageRepo->contact = self::entityToJson($message->contact);
        $messageRepo->location = self::entityToJson($message->location);
        $messageRepo->venue = self::entityToJson($message->venue);
        $messageRepo->poll = self::entityToJson($message->poll);
        $messageRepo->dice = self::entityToJson($message->dice);
        $messageRepo->new_chat_members = $new_chat_members_ids;
        $messageRepo->left_chat_member = $left_chat_member_id;
        $messageRepo->new_chat_title = $message->newChatTitle;
        $messageRepo->new_chat_photo = self::entitiesArrayToJson($message->newChatPhoto);
        $messageRepo->delete_chat_photo = boolval($message->deleteChatPhoto);
        $messageRepo->group_chat_created = boolval($message->groupChatCreated);
        $messageRepo->supergroup_chat_created = boolval($message->supergroupChatCreated);
        $messageRepo->channel_chat_created = boolval($message->channelChatCreated);
        $messageRepo->migrate_to_chat_id = $message->migrateToChatId;
        $messageRepo->migrate_from_chat_id = $message->migrateFromChatId;
        $messageRepo->pinned_message = self::entityToJson($message->pinnedMessage);
        $messageRepo->invoice = self::entityToJson($message->invoice);
        $messageRepo->successful_payment = self::entityToJson($message->successfulPayment);
        $messageRepo->connected_website = $message->connectedWebsite;
        $messageRepo->passport_data = self::entityToJson($message->passportData);
        $messageRepo->reply_markup = self::entityToJson($message->replyMarkup);

        if (!$messageRepo->save()) {
            Yii::warning(['Message save error', $messageRepo->errors], 'telegram');
            throw new TelegramException('Message save error');
        }

        return true;
    }

    /**
     * Insert Edited Message request in db
     *
     * @param EditedMessage|EditedChannelPost $edited_message
     *
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     * @throws BaseException
     */
    public static function editedMessageRequestInsert($edited_message)
    {
        $edit_date = self::getTimestamp($edited_message->editDate);

        // Insert chat
        $chat = $edited_message->chat;
        self::chatInsert($chat);

        // Insert user and the relation with the chat
        if ($user = $edited_message->from) {
            self::userUpsert($user, $chat);
        }

        $user_id = $user ? $user->id : null;

        $messageRepo = new EditedMessageRepo();
        $messageRepo->chat_id = $chat->id;
        $messageRepo->message_id = $edited_message->messageId;
        $messageRepo->user_id = $user_id;
        $messageRepo->edit_date = $edit_date;
        $messageRepo->text = $edited_message->text;
        $messageRepo->entities = self::entitiesArrayToJson($edited_message->entities);
        $messageRepo->caption = $edited_message->caption;

        if (!$messageRepo->insert()) {
            Yii::warning(['Edited Message save error', $messageRepo->errors], 'telegram');
            throw new TelegramException('Edited Message save error');
        }

        $edited_message->edited_message_id = $messageRepo->id;

        return true;
    }

    /**
     * Insert inline query request into database
     *
     * @param InlineQuery $inline_query
     *
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     * @throws BaseException
     */
    public static function inlineQueryRequestInsert(InlineQuery $inline_query)
    {
        $user_id = null;

        if ($user = $inline_query->from) {
            $user_id = $user->id;
            self::userUpsert($user);
        }

        $queryRepo = InlineQueryRepo::findOne($inline_query->id);
        if ($queryRepo === null) {
            $queryRepo = new InlineQueryRepo(['id' => $inline_query->id]);
        }

        $queryRepo->user_id = $user_id;
        $queryRepo->location = self::entityToJson($inline_query->location);
        $queryRepo->query = $inline_query->query;
        $queryRepo->offset = $inline_query->offset;

        if (!$queryRepo->save()) {
            Yii::warning(['Inline query save error', $queryRepo->errors], 'telegram');
            throw new TelegramException('Inline query save error');
        }

        return true;
    }

    /**
     * Insert chosen inline result request into database
     *
     * @param ChosenInlineResult $chosen_inline_result
     *
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     * @throws BaseException
     */
    public static function chosenInlineResultRequestInsert(ChosenInlineResult $chosen_inline_result)
    {
        $user_id = null;

        if ($user = $chosen_inline_result->from) {
            $user_id = $user->id;
            self::userUpsert($user);
        }

        $resultRepo = new ChosenInlineResultRepo();
        $resultRepo->result_id = $chosen_inline_result->resultId;
        $resultRepo->user_id = $user_id;
        $resultRepo->location = self::entityToJson($chosen_inline_result->location);
        $resultRepo->query = $chosen_inline_result->query;
        $resultRepo->inline_message_id = $chosen_inline_result->inlineMessageId;

        if (!$resultRepo->save()) {
            Yii::warning(['Inline result save error', $resultRepo->errors], 'telegram');
            throw new TelegramException('Inline result save error');
        }

        $chosen_inline_result->chosen_inline_result_id = $resultRepo->id;

        return true;
    }

    /**
     * Insert callback query request into database
     *
     * @param CallbackQuery $callback_query
     *
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     * @throws BaseException
     */
    public static function callbackQueryRequestInsert(CallbackQuery $callback_query)
    {
        $queryRepo = CallbackQueryRepo::findOne(['id' => $callback_query->id]);
        if ($queryRepo === null) {
            $queryRepo = new CallbackQueryRepo(['id' => $callback_query->id]);
        }

        $user_id = null;

        if ($user = $callback_query->from) {
            $user_id = $user->id;
            self::userUpsert($user);
        }

        $chat_id    = null;
        $message_id = null;
        if ($message = $callback_query->message) {
            $chat_id = $message->chat->id;
            $message_id = $message->messageId;

            $is_message = MessageRepo::find()->where(['chat_id' => $chat_id, 'id' => $message_id])->exists();
            if ($is_message) {
                self::editedMessageRequestInsert($message);
            } else {
                self::messageRequestInsert($message);
            }
        }

        $queryRepo->user_id = $user_id;
        $queryRepo->chat_id = $chat_id;
        $queryRepo->message_id = $message_id;
        $queryRepo->inline_message_id = $callback_query->inlineMessageId;
        $queryRepo->chat_instance = $callback_query->chatInstance;
        $queryRepo->data = $callback_query->data;
        $queryRepo->game_short_name = $callback_query->gameShortName;

        if (!$queryRepo->save()) {
            Yii::warning(['Callback query save error', $queryRepo->errors], 'telegram');
            throw new TelegramException('Callback query save error');
        }

        return true;
    }

    /**
     * Insert shipping query request into database
     *
     * @param ShippingQuery $shipping_query
     *
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     * @throws BaseException
     */
    public static function shippingQueryRequestInsert(ShippingQuery $shipping_query)
    {
        $queryRepo = ShippingQueryRepo::findOne(['id' => $shipping_query->id]);
        if ($queryRepo === null) {
            $queryRepo = new ShippingQueryRepo(['id' => $shipping_query->id]);
        }

        $user_id = null;
        if ($user = $shipping_query->from) {
            $user_id = $user->id;
            self::userUpsert($user);
        }

        $queryRepo->user_id = $user_id;
        $queryRepo->invoice_payload = $shipping_query->invoicePayload;
        $queryRepo->shipping_address = self::entityToJson($shipping_query->shippingAddress);

        if (!$queryRepo->save()) {
            Yii::warning(['Shipping query save error', $queryRepo->errors], 'telegram');
            throw new TelegramException('Shipping query save error');
        }

        return true;
    }

    /**
     * Insert pre checkout query request into database
     *
     * @param PreCheckoutQuery $pre_checkout_query
     *
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     * @throws BaseException
     */
    public static function preCheckoutQueryRequestInsert(PreCheckoutQuery $pre_checkout_query)
    {
        $queryRepo = PreCheckoutQueryRepo::findOne(['id' => $pre_checkout_query->id]);
        if ($queryRepo === null) {
            $queryRepo = new PreCheckoutQueryRepo(['id' => $pre_checkout_query->id]);
        }

        $user_id = null;
        if ($user = $pre_checkout_query->from) {
            $user_id = $user->id;
            self::userUpsert($user);
        }

        $queryRepo->user_id = $user_id;
        $queryRepo->currency = $pre_checkout_query->currency;
        $queryRepo->total_amount = $pre_checkout_query->totalAmount;
        $queryRepo->invoice_payload = $pre_checkout_query->invoicePayload;
        $queryRepo->shipping_option_id = $pre_checkout_query->shippingOptionId;
        $queryRepo->order_info = $pre_checkout_query->orderInfo;

        if (!$queryRepo->save()) {
            Yii::warning(['PreCheckout query save error', $queryRepo->errors], 'telegram');
            throw new TelegramException('PreCheckout query save error');
        }

        return true;
    }

    /**
     * Insert poll request into database
     *
     * @param Poll $poll
     *
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     * @throws BaseException
     */
    public static function pollRequestInsert(Poll $poll)
    {
        $pollRepo = PollRepo::findOne(['id' => $poll->id]);
        if ($pollRepo === null) {
            $pollRepo = new PollRepo(['id' => $poll->id]);
        }

        $pollRepo->question = $poll->question;
        $pollRepo->options = self::entitiesArrayToJson($poll->options);
        $pollRepo->total_voter_count = $poll->totalVoterCount;
        $pollRepo->is_closed = boolval($poll->isClosed);
        $pollRepo->is_anonymous = boolval($poll->isAnonymous);
        $pollRepo->type = $poll->type;
        $pollRepo->allows_multiple_answers = boolval($poll->allowsMultipleAnswers);
        $pollRepo->correct_option_id = $poll->correctOptionId;
        $pollRepo->explanation = $poll->explanation;
        $pollRepo->explanation_entities = self::entitiesArrayToJson($poll->explanationEntities);
        $pollRepo->open_period = $poll->openPeriod;
        $pollRepo->close_date = self::getTimestamp($poll->closeDate);

        if (!$pollRepo->save()) {
            Yii::warning(['Poll save error', $pollRepo->errors], 'telegram');
            throw new TelegramException('Poll save error');
        }

        return true;
    }

    /**
     * Insert poll answer request into database
     *
     * @param PollAnswer $poll_answer
     *
     * @return bool If the insert was successful
     *
     * @throws TelegramException
     * @throws BaseException
     */
    public static function pollAnswerRequestInsert(PollAnswer $poll_answer)
    {
        $user_id = null;
        if ($user = $poll_answer->user) {
            $user_id = $user->id;
            self::userUpsert($user);
        }

        $answerRepo = PollAnswerRepo::findOne(['poll_id' => $poll_answer->pollId, 'user_id' => $user_id]);
        if ($answerRepo === null) {
            $answerRepo = new PollAnswerRepo(['poll_id' => $poll_answer->pollId, 'user_id' => $user_id]);
        }

        $answerRepo->option_ids = $poll_answer->optionIds;

        if (!$answerRepo->save()) {
            Yii::warning(['Poll answer save error', $answerRepo->errors], 'telegram');
            throw new TelegramException('Poll answer save error');
        }

        return true;
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
     * @throws BaseException
     */
    public static function insertTelegramRequest($method, $data)
    {
        $chat_id = isset($data['chat_id']) ? $data['chat_id'] : null;
        $inline_message_id = isset($data['inline_message_id']) ? $data['inline_message_id'] : null;

        $limiter = new RequestLimiter();
        $limiter->chat_id = $chat_id;
        $limiter->inline_message_id = $inline_message_id;
        $limiter->method = $method;

        if (!$limiter->save()) {
            Yii::warning(['Request limiter save error', $limiter->errors], 'telegram');
            throw new TelegramException('Request limiter save error');
        }

        return true;
    }

    /**
     * Get Telegram API request count for current chat / message
     *
     * @param integer $chat_id
     * @param string  $inline_message_id
     *
     * @return array Array containing TOTAL and CURRENT fields or false on invalid arguments
     */
    public static function getTelegramRequestCount($chat_id = null, $inline_message_id = null)
    {
        $date = self::getTimestamp();
        $date_minute = self::getTimestamp(strtotime('-1 minute'));

        return [
            'LIMIT_PER_SEC_ALL' => RequestLimiter::find()
                ->where(['>=', 'created_at', $date])
                ->select('chat_id')
                ->distinct()
                ->count(),
            'LIMIT_PER_SEC' => RequestLimiter::find()
                ->where(['>=', 'created_at', $date_minute])
                ->andWhere([
                    'or',
                    ['chat_id' => $chat_id, 'inline_message_id' => null],
                    ['chat_id' => null, 'inline_message_id' => $inline_message_id],
                ])
                ->count(),
            'LIMIT_PER_MINUTE' => RequestLimiter::find()
                ->where(['>=', 'created_at', $date_minute])
                ->andWhere(['chat_id' => $chat_id])
                ->count()
        ];
    }
    //</editor-fold>
}
