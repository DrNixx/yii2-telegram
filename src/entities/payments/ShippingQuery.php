<?php
namespace onix\telegram\entities\payments;

use onix\telegram\entities\Entity;
use onix\telegram\entities\ServerResponse;
use onix\telegram\entities\User;

/**
 * Class ShippingQuery
 *
 * This object contains information about an incoming shipping query.
 *
 * @link https://core.telegram.org/bots/api#shippingquery
 *
 * @property-read string $id Unique query identifier
 * @property-read User $from User who sent the query
 * @property-read string $invoicePayload Bot specified invoice payload
 * @property-read ShippingAddress $shippingAddress User specified shipping address
 **/
class ShippingQuery extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['id', 'from', 'invoicePayload', 'shippingAddress'];
    }
    
    /**
     * @inheritDoc
     */
    protected function subEntities(): array
    {
        return [
            'from' => User::class,
            'shippingAddress' => ShippingAddress::class,
        ];
    }

    /**
     * Answer this shipping query.
     *
     * @param bool  $ok
     * @param array $data
     *
     * @return ServerResponse
     */
    public function answer($ok, array $data = [])
    {
        return $this->telegram->request->answerShippingQuery(array_merge([
            'shipping_query_id' => $this->id,
            'ok' => $ok,
        ], $data));
    }
}
