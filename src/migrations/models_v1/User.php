<?php

namespace onix\telegram\migrations\models_v1;

use onix\data\ActiveRecordEx;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "telegram.user".
 *
 * @property int $id Unique identifier for this user or bot
 * @property bool $is_bot True, if this user is a bot
 * @property int|null $user_id Identifier for chess user
 * @property string $first_name User's or bot's first name
 * @property string|null $last_name User's or bot's last name
 * @property string|null $username User's or bot's last username
 * @property string|null $language_code IETF language tag of the user's language
 * @property string $created_at Entry date creation
 * @property string $updated_at Entry date update
 *
 * @property CallbackQuery[] $callbackQueries
 * @property Chat[] $chats
 * @property ChosenInlineResult[] $chosenInlineResults
 * @property Conversation[] $conversations
 * @property EditedMessage[] $editedMessages
 * @property InlineQuery[] $inlineQueries
 * @property Message[] $sendMessages
 * @property Message[] $forwardMessages
 * @property Message[] $botMessages
 * @property Message[] $leaveChatMessages
 * @property PollAnswer[] $pollAnswers
 * @property Poll[] $polls
 * @property PreCheckoutQuery[] $preCheckoutQueries
 * @property ShippingQuery[] $shippingQueries
 * @property UserChat[] $userChats
 */
class User extends ActiveRecordEx
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram.user';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $now = (self::getDb()->driverName === 'pgsql') ? "timezone('GMT'::text, now())" : 'now()';

        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression($now),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'first_name'], 'required'],
            [['id', 'user_id'], 'default', 'value' => null],
            [['id', 'user_id'], 'number'],
            [['is_bot'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['first_name', 'last_name'], 'string', 'max' => 255],
            [['username'], 'string', 'max' => 191],
            [['language_code'], 'string', 'max' => 10],
            [['id'], 'unique'],
        ];
    }

    /**
     * Gets query for [[CallbackQueries]].
     *
     * @return ActiveQuery|CallbackQueryQuery
     */
    public function getCallbackQueries()
    {
        return $this->hasMany(CallbackQuery::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Chats]].
     *
     * @return ActiveQuery|ChatQuery
     */
    public function getChats()
    {
        return $this->hasMany(Chat::class, ['id' => 'chat_id'])->via('userChats');
    }

    /**
     * Gets query for [[ChosenInlineResults]].
     *
     * @return ActiveQuery|ChosenInlineResultQuery
     */
    public function getChosenInlineResults()
    {
        return $this->hasMany(ChosenInlineResult::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Conversations]].
     *
     * @return ActiveQuery|ConversationQuery
     */
    public function getConversations()
    {
        return $this->hasMany(Conversation::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[EditedMessages]].
     *
     * @return ActiveQuery|EditedMessageQuery
     */
    public function getEditedMessages()
    {
        return $this->hasMany(EditedMessage::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[InlineQueries]].
     *
     * @return ActiveQuery|InlineQueryQuery
     */
    public function getInlineQueries()
    {
        return $this->hasMany(InlineQuery::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]] as sender.
     *
     * @return ActiveQuery|MessageQuery
     */
    public function getSendMessages()
    {
        return $this->hasMany(Message::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]] as forwarding.
     *
     * @return ActiveQuery|MessageQuery
     */
    public function getForwardMessages()
    {
        return $this->hasMany(Message::class, ['forward_from' => 'id']);
    }

    /**
     * Gets query for [[Messages]] as bot.
     *
     * @return ActiveQuery|MessageQuery
     */
    public function getBotMessages()
    {
        return $this->hasMany(Message::class, ['via_bot' => 'id']);
    }

    /**
     * Gets query for [[Messages]] as leaver.
     *
     * @return ActiveQuery|MessageQuery
     */
    public function getLeaveChatMessages()
    {
        return $this->hasMany(Message::class, ['left_chat_member' => 'id']);
    }

    /**
     * Gets query for [[PollAnswers]].
     *
     * @return ActiveQuery|PollAnswerQuery
     */
    public function getPollAnswers()
    {
        return $this->hasMany(PollAnswer::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Polls]].
     *
     * @return ActiveQuery|PollQuery
     */
    public function getPolls()
    {
        return $this->hasMany(Poll::class, ['id' => 'poll_id'])->via('pollAnswers');
    }

    /**
     * Gets query for [[PreCheckoutQueries]].
     *
     * @return ActiveQuery|PreCheckoutQueryQuery
     */
    public function getPreCheckoutQueries()
    {
        return $this->hasMany(PreCheckoutQuery::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[ShippingQueries]].
     *
     * @return ActiveQuery|ShippingQueryQuery
     */
    public function getShippingQueries()
    {
        return $this->hasMany(ShippingQuery::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserChats]].
     *
     * @return ActiveQuery|UserChatQuery
     */
    public function getUserChats()
    {
        return $this->hasMany(UserChat::class, ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find($alias = null)
    {
        return new UserQuery(get_called_class(), ['as' => $alias]);
    }
}
