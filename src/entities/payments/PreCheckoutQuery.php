<?php
namespace onix\telegram\entities\payments;

use onix\telegram\entities\Entity;
use onix\telegram\entities\ServerResponse;
use onix\telegram\entities\User;

/**
 * Class PreCheckoutQuery
 *
 * This object contains information about an incoming pre-checkout query.
 *
 * @link https://core.telegram.org/bots/api#precheckoutquery
 *
 * @property-read string $id Unique query identifier
 * @property-read User $from User who sent the query
 * @property-read string $currency Three-letter ISO 4217 currency code
 * @property-read int $totalAmount Total price in the smallest units of the currency (integer, not float/double).
 * @property-read string $invoicePayload Bot specified invoice payload
 * @property-read string $shippingOptionId Optional. Identifier of the shipping option chosen by the user
 * @property-read OrderInfo $orderInfo Optional. Order info provided by the user
 **/
class PreCheckoutQuery extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['id', 'from', 'currency', 'totalAmount', 'invoicePayload', 'shippingOptionId', 'orderInfo'];
    }
    
    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'from' => User::class,
            'orderInfo' => OrderInfo::class,
        ];
    }

    /**
     * Answer this pre-checkout query.
     *
     * @param bool  $ok
     * @param array $data
     *
     * @return ServerResponse
     */
    public function answer($ok, array $data = [])
    {
        return $this->telegram->request->answerPreCheckoutQuery(array_merge([
            'pre_checkout_query_id' => $this->id,
            'ok' => $ok,
        ], $data));
    }
}
