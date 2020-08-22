<?php
namespace onix\telegram\entities\payments;

use onix\telegram\entities\Entity;

/**
 * Class Invoice
 *
 * This object contains basic information about an invoice.
 *
 * @link https://core.telegram.org/bots/api#invoice
 *
 * @property-read string $title Product name
 * @property-read string $description Product description
 * @property-read string $startParameter Unique bot deep-linking parameter that can be used to generate this invoice
 * @property-read string $currency Three-letter ISO 4217 currency code
 * @property-read int $totalAmount Total price in the smallest units of the currency (integer, not float/double).
 **/
class Invoice extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['title', 'description', 'startParameter', 'currency', 'totalAmount'];
    }
}
