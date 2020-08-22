<?php
namespace onix\telegram\entities\passport;

use onix\telegram\entities\Entity;

/**
 * Class PassportData
 *
 * Contains information about Telegram Passport data shared with the bot by the user.
 *
 * @link https://core.telegram.org/bots/api#passportdata
 *
 * @property-read EncryptedPassportElement[] $data Array with information about documents and other Telegram Passport
 * elements that was shared with the bot
 *
 * @property-read EncryptedCredentials $credentials Encrypted credentials required to decrypt the data
 **/
class PassportData extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['data', 'credentials'];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'data' => [EncryptedPassportElement::class],
            'credentials' => EncryptedCredentials::class,
        ];
    }
}
