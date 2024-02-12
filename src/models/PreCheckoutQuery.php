<?php
namespace onix\telegram\models;

use onix\telegram\entities\payments\OrderInfo;
use onix\telegram\entities\payments\PreCheckoutQuery as PreCheckoutQueryEntity;

/**
 * This is the model class for table "telegram.pre_checkout_query".
 *
 * @property string $_id Unique query identifier
 * @property int|null $userId User who sent the query
 * @property string|null $currency Three-letter ISO 4217 currency code
 * @property int|null $totalAmount Total price in the smallest units of the currency
 * @property string $invoicePayload Bot specified invoice payload
 * @property string|null $shippingOptionId Identifier of the shipping option chosen by the user
 * @property OrderInfo|null $orderInfo Order info provided by the user
 */
class PreCheckoutQuery extends TelegramActiveRecord
{
    protected ?string $entityClass = PreCheckoutQueryEntity::class;

    protected array $attributeMap = [
        'id' => '_id',
        'from' => 'userId'
    ];

    /**
     * {@inheritdoc}
     */
    public static function collectionName(): array|string
    {
        return 'telegram_pre_checkout_query';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['_id', 'invoicePayload'], 'required'],
            [['_id'], 'string'],
            [['userId'], 'integer'],
            [['totalAmount'], 'number'],
            [['orderInfo'], 'safe'],
            [['currency'], 'string', 'max' => 3],
            [['invoicePayload', 'shippingOptionId'], 'string', 'max' => 255],
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
     * @return PreCheckoutQueryQuery the active query used by this AR class.
     */
    public static function find(): PreCheckoutQueryQuery
    {
        return new PreCheckoutQueryQuery(get_called_class());
    }
}
