<?php
namespace onix\telegram\migrations\models_v1;

use onix\data\ActiveRecordEx;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "telegram.shipping_query".
 *
 * @property int $id Unique query identifier
 * @property int|null $user_id User who sent the query
 * @property string $invoice_payload Bot specified invoice payload
 * @property string $shipping_address User specified shipping address
 * @property string $created_at Entry date creation
 *
 * @property TelegramUpdate[] $telegramUpdates
 * @property User $user
 */
class ShippingQuery extends ActiveRecordEx
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram.shipping_query';
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
            [['id', 'invoice_payload', 'shipping_address'], 'required'],
            [['id', 'user_id'], 'default', 'value' => null],
            [['id', 'user_id'], 'number'],
            [['created_at'], 'safe'],
            [['invoice_payload', 'shipping_address'], 'string', 'max' => 255],
            [['id'], 'unique'],
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
     * Gets query for [[TelegramUpdates]].
     *
     * @return ActiveQuery|TelegramUpdateQuery
     */
    public function getTelegramUpdates()
    {
        return $this->hasMany(TelegramUpdate::class, ['shipping_query_id' => 'id']);
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
     * @return ShippingQueryQuery the active query used by this AR class.
     */
    public static function find($alias = null)
    {
        return new ShippingQueryQuery(get_called_class(), ['as' => $alias]);
    }
}
