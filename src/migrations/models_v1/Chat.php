<?php
namespace onix\telegram\migrations\models_v1;

use onix\data\ActiveRecordEx;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "telegram.chat".
 *
 * @property int $id Unique identifier for this chat
 * @property string $type Type of chat, can be either private, group, supergroup or channel
 * @property string $title Title, for supergroups, channels and group chats
 * @property string|null $first_name First name of the other party in a private chat
 * @property string|null $last_name Last name of the other party in a private chat
 * @property string|null $username Username, for private chats, supergroups and channels if available
 * @property bool $all_members_are_administrators True if a all members of this group are admins
 * @property string $created_at Entry date creation
 * @property string $updated_at Entry date update
 * @property int|null $old_id Unique chat identifier, this is filled when a group is converted to a supergroup
 *
 * @property CallbackQuery[] $callbackQueries
 * @property Conversation[] $conversations
 * @property EditedMessage[] $editedMessages
 * @property Message[] $messages
 * @property Message[] $forwardMessages
 * @property RequestLimiter[] $requestLimiters
 * @property UserChat[] $userChats
 * @property User[] $users
 */
class Chat extends ActiveRecordEx
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram.chat';
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
            [['id', 'type'], 'required'],
            [['id', 'old_id'], 'default', 'value' => null],
            [['id', 'old_id'], 'number'],
            [['all_members_are_administrators'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['type'], 'string', 'max' => 15],
            [['title', 'first_name', 'last_name', 'username'], 'string', 'max' => 255],
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
        return $this->hasMany(CallbackQuery::class, ['chat_id' => 'id']);
    }

    /**
     * Gets query for [[Conversations]].
     *
     * @return ActiveQuery|ConversationQuery
     */
    public function getConversations()
    {
        return $this->hasMany(Conversation::class, ['chat_id' => 'id']);
    }

    /**
     * Gets query for [[EditedMessages]].
     *
     * @return ActiveQuery|EditedMessageQuery
     */
    public function getEditedMessages()
    {
        return $this->hasMany(EditedMessage::class, ['chat_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]] as send.
     *
     * @return ActiveQuery|MessageQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::class, ['chat_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]] as forwarding.
     *
     * @return ActiveQuery|MessageQuery
     */
    public function getForwardMessages()
    {
        return $this->hasMany(Message::class, ['forward_from_chat' => 'id']);
    }

    /**
     * Gets query for [[RequestLimiters]].
     *
     * @return ActiveQuery|RequestLimiterQuery
     */
    public function getRequestLimiters()
    {
        return $this->hasMany(RequestLimiter::class, ['chat_id' => 'id']);
    }

    /**
     * Gets query for [[UserChats]].
     *
     * @return ActiveQuery|UserChatQuery
     */
    public function getUserChats()
    {
        return $this->hasMany(UserChat::class, ['chat_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->via('userChats');
    }

    /**
     * {@inheritdoc}
     * @return ChatQuery the active query used by this AR class.
     */
    public static function find($alias = null)
    {
        return new ChatQuery(get_called_class(), ['as' => $alias]);
    }
}
