<?php
namespace onix\telegram\entities\payments;

use onix\telegram\entities\Entity;

/**
 * Class SuccessfulPayment
 *
 * This object contains basic information about a successful payment.
 *
 * @link https://core.telegram.org/bots/api#successfulpayment
 *
 * @property-read string $currency Three-letter ISO 4217 currency code
 * @property-read int $totalAmount Total price in the smallest units of the currency (integer, not float/double).
 * @property-read string $invoicePayload Bot specified invoice payload
 * @property-read string $shippingOptionId Optional. Identifier of the shipping option chosen by the user
 * @property-read OrderInfo $orderInfo Optional. Order info provided by the user
 * @property-read string $telegramPaymentChargeId Telegram payment identifier
 * @property-read string $providerPaymentChargeId Provider payment identifier
 **/
class SuccessfulPayment extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return [
            'currency',
            'totalAmount',
            'invoicePayload',
            'shippingOptionId',
            'orderInfo',
            'telegramPaymentChargeId',
            'providerPaymentChargeId'
        ];
    }
    
    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'orderInfo' => OrderInfo::class,
        ];
    }
}
