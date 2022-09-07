<?php
namespace onix\telegram\entities\payments;

use onix\telegram\entities\Entity;

/**
 * Class ShippingOption
 *
 * This object represents one shipping option.
 *
 * @link https://core.telegram.org/bots/api#shippingoption
 *
 * @property-read string $id Shipping option identifier
 * @property-read string $title Option title
 * @property-read LabeledPrice[] $prices List of price portions
 **/
class ShippingOption extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['id', 'title', 'prices'];
    }
    
    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'prices' => [LabeledPrice::class],
        ];
    }
}
