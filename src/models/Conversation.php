<?php
namespace onix\telegram\models;

use onix\data\ActiveRecordEx;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "telegram.conversation".
 *
 * @property int $id Unique identifier for this entry
 * @property int|null $user_id Unique user identifier
 * @property int|null $chat_id Unique chat identifier
 * @property string $status Identifier of the message sent via the bot in inline mode, that originated the query
 * @property string $command Default command to execute
 * @property string|null $notes Data stored from command
 * @property string $created_at Entry date creation
 * @property string $updated_at Entry date update
 *
 * @property Chat $chat
 * @property User $user
 */
class Conversation extends ActiveRecordEx
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram.conversation';
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
            [['user_id', 'chat_id'], 'default', 'value' => null],
            [['user_id', 'chat_id'], 'number'],
            [['notes'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string', 'max' => 15],
            [['command'], 'string', 'max' => 160],
            [
                ['chat_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Chat::class,
                'targetAttribute' => ['chat_id' => 'id']
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
     * @return ConversationQuery the active query used by this AR class.
     */
    public static function find($alias = null)
    {
        return new ConversationQuery(get_called_class(), ['as' => $alias]);
    }
}
