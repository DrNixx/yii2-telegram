<?php
namespace onix\telegram\entities\passport\errors;

use onix\telegram\entities\Entity;

/**
 * Class ErrorEntity
 *
 * @property-read string $source Error source, must be file
 * @property-read string $type  The section of the user's Telegram Passport which has the issue, one of "utility_bill",
 * "bank_statement", "rental_agreement", "passport_registration", "temporary_registration"
 *
 * @property-read string $message Error message
 */
abstract class ErrorEntity extends Entity implements PassportElementError
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['source', 'type', 'message'];
    }
}
