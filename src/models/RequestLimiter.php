<?php
namespace onix\telegram\models;

use onix\data\ActiveRecordEx;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "telegram.request_limiter".
 *
 * @property int $id Unique identifier for this entry
 * @property int|null $chat_id Unique chat identifier
 * @property string|null $inline_message_id Identifier of the sent inline message
 * @property string|null $method Request method
 * @property string $created_at Entry date creation
 *
 * @property Chat $chat
 */
class RequestLimiter extends ActiveRecordEx
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram.request_limiter';
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
            [['chat_id'], 'default', 'value' => null],
            [['chat_id'], 'integer'],
            [['created_at'], 'safe'],
            [['inline_message_id', 'method'], 'string', 'max' => 255],
            [
                ['chat_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Chat::class,
                'targetAttribute' => ['chat_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('telegram', 'Unique identifier for this entry'),
            'chat_id' => Yii::t('telegram', 'Unique chat identifier'),
            'inline_message_id' => Yii::t('telegram', 'Identifier of the sent inline message'),
            'method' => Yii::t('telegram', 'Request method'),
            'created_at' => Yii::t('telegram', 'Entry date creation'),
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
     * {@inheritdoc}
     * @return RequestLimiterQuery the active query used by this AR class.
     */
    public static function find($alias = null)
    {
        return new RequestLimiterQuery(get_called_class(), ['as' => $alias]);
    }
}
