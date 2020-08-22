<?php
namespace onix\telegram\entities\inputMediaContent;

use onix\telegram\entities\Entity;

/**
 * Class InputContactMessageContent
 *
 * @link https://core.telegram.org/bots/api#inputcontactmessagecontent
 *
 * <code>
 * $data = [
 *   'phone_number' => '',
 *   'first_name' => '',
 *   'last_name' => '',
 *   'vcard' => '',
 * ];
 * </code>
 *
 * @property string $phoneNumber Contact's phone number
 * @property string $firstName Contact's first name
 * @property string $lastName Optional. Contact's last name
 * @property string $vcard Optional. Additional data about the contact in the form of a vCard, 0-2048 bytes
 */
class InputContactMessageContent extends Entity implements InputMessageContent
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['phoneNumber', 'firstName', 'lastName', 'vcard'];
    }
}
