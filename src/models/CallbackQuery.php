<?php
namespace onix\telegram\models;

use onix\data\ActiveRecordEx;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "telegram.callback_query".
 *
 * @property int $id Unique identifier for this query
 * @property int|null $user_id Unique user identifier
 * @property int|null $chat_id Unique chat identifier
 * @property int|null $message_id Unique message identifier
 * @property string|null $inline_message_id Identifier of the message sent via the bot in inline mode,
 * that originated the query
 *
 * @property string|null $chat_instance Global identifier, uniquely corresponding to the chat to which the message
 * with the callback button was sent
 *
 * @property string|null $data Data associated with the callback button
 * @property string|null $game_short_name Short name of a Game to be returned, serves as the unique identifier
 * for the game
 *
 * @property string $created_at Entry date creation
 *
 * @property Chat $chat
 * @property Message $message
 * @property TelegramUpdate[] $telegramUpdates
 * @property User $user
 */
class CallbackQuery extends ActiveRecordEx
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram.callback_query';
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => [],
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
            [['id'], 'required'],
            [['id', 'user_id', 'chat_id', 'message_id'], 'default', 'value' => null],
            [['id', 'user_id', 'chat_id', 'message_id'], 'number'],
            [['created_at'], 'safe'],
            [['inline_message_id', 'chat_instance', 'data', 'game_short_name'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [
                ['chat_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Chat::class,
                'targetAttribute' => ['chat_id' => 'id']
            ],
            [
                ['chat_id', 'message_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Message::class,
                'targetAttribute' => ['chat_id' => 'chat_id', 'message_id' => 'id']
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
        ];
    }

    /**
     * Gets query for [[Chat]].
     *
     * @return ActiveQuery|ChatQuery
     */
    public function getChat()
    {
        return $this->hasOne(Chat::class, ['id' => 'chat_id']);
    }

    /**
     * Gets query for [[Chat0]].
     *
     * @return ActiveQuery|MessageQuery
     */
    public function getMessage()
    {
        return $this->hasOne(Message::class, ['chat_id' => 'chat_id', 'id' => 'message_id']);
    }

    /**
     * Gets query for [[TelegramUpdates]].
     *
     * @return ActiveQuery|TelegramUpdateQuery
     */
    public function getTelegramUpdates()
    {
        return $this->hasMany(TelegramUpdate::class, ['callback_query_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return CallbackQueryQuery the active query used by this AR class.
     */
    public static function find($alias = null)
    {
        return new CallbackQueryQuery(get_called_class(), ['as' => $alias]);
    }
}
