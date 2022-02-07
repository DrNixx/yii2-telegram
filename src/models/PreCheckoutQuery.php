<?php
namespace onix\telegram\models;

use onix\data\ActiveRecordEx;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "telegram.pre_checkout_query".
 *
 * @property int $id Unique query identifier
 * @property int|null $user_id User who sent the query
 * @property string|null $currency Three-letter ISO 4217 currency code
 * @property int|null $total_amount Total price in the smallest units of the currency
 * @property string $invoice_payload Bot specified invoice payload
 * @property string|null $shipping_option_id Identifier of the shipping option chosen by the user
 * @property string|null $order_info Order info provided by the user
 * @property string $created_at Entry date creation
 *
 * @property TelegramUpdate[] $telegramUpdates
 * @property User $user
 */
class PreCheckoutQuery extends ActiveRecordEx
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram.pre_checkout_query';
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
            [['id', 'invoice_payload'], 'required'],
            [['id', 'user_id', 'total_amount'], 'default', 'value' => null],
            [['id', 'user_id', 'total_amount'], 'number'],
            [['order_info'], 'string'],
            [['created_at'], 'safe'],
            [['currency'], 'string', 'max' => 3],
            [['invoice_payload', 'shipping_option_id'], 'string', 'max' => 255],
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
        return $this->hasMany(TelegramUpdate::class, ['pre_checkout_query_id' => 'id']);
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
     * @return PreCheckoutQueryQuery the active query used by this AR class.
     */
    public static function find($alias = null)
    {
        return new PreCheckoutQueryQuery(get_called_class(), ['as' => $alias]);
    }
}
