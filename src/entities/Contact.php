<?php
namespace onix\telegram\entities;

/**
 * Class Contact
 *
 * @link https://core.telegram.org/bots/api#contact
 *
 * @property-read string $phoneNumber Contact's phone number
 * @property-read string $firstName Contact's first name
 * @property-read string $lastName Optional. Contact's last name
 * @property-read int $userId Optional. Contact's user identifier in Telegram
 * @property-read string $vcard Optional. Additional data about the contact in the form of a vCard
 */
class Contact extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['phoneNumber', 'firstName', 'lastName', 'userId', 'vcard'];
    }
}
