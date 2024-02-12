<?php
namespace onix\telegram\entities\payments;

use onix\telegram\entities\Entity;

/**
 * Class ShippingAddress
 *
 * This object represents a shipping address.
 *
 * @link https://core.telegram.org/bots/api#shippingaddress
 *
 * @property-read string $countryCode ISO 3166-1 alpha-2 country code
 * @property-read string $state State, if applicable
 * @property-read string $city City
 * @property-read string $streetLine1 First line for the address
 * @property-read string $streetLine2 Second line for the address
 * @property-read string $postCode Address post code
 **/
class ShippingAddress extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['countryCode', 'state', 'city', 'streetLine1', 'streetLine2', 'postCode'];
    }
}
