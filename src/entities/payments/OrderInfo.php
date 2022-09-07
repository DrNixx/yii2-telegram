<?php
namespace onix\telegram\entities\payments;

use onix\telegram\entities\Entity;

/**
 * Class OrderInfo
 *
 * This object represents information about an order.
 *
 * @link https://core.telegram.org/bots/api#orderinfo
 *
 * @property-read string $name Optional. User name
 * @property-read string $phoneNumber Optional. User's phone number
 * @property-read string $email Optional. User email
 * @property-read ShippingAddress $shippingAddress Optional. User shipping address
 **/
class OrderInfo extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['name', 'phoneNumber', 'email', 'shippingAddress'];
    }
    
    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'shippingAddress' => ShippingAddress::class,
        ];
    }
}
