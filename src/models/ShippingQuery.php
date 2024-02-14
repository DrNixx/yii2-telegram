<?php
namespace onix\telegram\models;

use onix\data\ActiveRecordEx;
use onix\telegram\entities\payments\ShippingAddress;
use onix\telegram\entities\payments\ShippingQuery as ShippingQueryEntity;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "telegram.shipping_query".
 *
 * @property string $_id Unique query identifier
 * @property int|null $userId User who sent the query
 * @property string $invoicePayload Bot specified invoice payload
 * @property ShippingAddress $shippingAddress User specified shipping address
 */
class ShippingQuery extends TelegramActiveRecord
{
    protected ?string $entityClass = ShippingQueryEntity::class;

    protected array $attributeMap = [
        'id' => '_id',
        'from' => 'userId'
    ];

    /**
     * {@inheritdoc}
     */
    public static function collectionName(): array|string
    {
        return 'telegram_shipping_query';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['_id', 'invoicePayload', 'shippingAddress'], 'required'],
            [['_id'], 'string'],
            [['userId'], 'integer'],
            [['shippingAddress'], 'safe'],
            [['invoicePayload'], 'string', 'max' => 255],
            [
                ['userId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['userId' => '_id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     * @return ShippingQueryQuery the active query used by this AR class.
     */
    public static function find(): ShippingQueryQuery
    {
        return new ShippingQueryQuery(get_called_class());
    }
}
