<?php
namespace onix\telegram\entities\payments;

use onix\telegram\entities\Entity;

/**
 * Class LabeledPrice
 *
 * This object represents a portion of the price for goods or services.
 *
 * @link https://core.telegram.org/bots/api#labeledprice
 *
 * @property-read string $label Portion label
 * @property-read int $amount Price of the product in the smallest units of the currency (integer, not float/double).
 **/
class LabeledPrice extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['label', 'amount'];
    }
}
