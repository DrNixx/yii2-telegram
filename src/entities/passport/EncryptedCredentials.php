<?php
namespace onix\telegram\entities\passport;

use onix\telegram\entities\Entity;

/**
 * Class EncryptedCredentials
 *
 * Contains data required for decrypting and authenticating EncryptedCredentials.
 * See the Telegram Passport Documentation for a complete description of the data decryption
 * and authentication processes.
 *
 * @link https://core.telegram.org/bots/api#encryptedcredentials
 *
 * @property-read string $data Base64-encoded encrypted JSON-serialized data with unique user's payload, data hashes
 * and secrets required for EncryptedPassportElement decryption and authentication
 *
 * @property-read string $hash Base64-encoded data hash for data authentication
 * @property-read string $secret() Base64-encoded secret, encrypted with the bot's public RSA key, required
 * for data decryption
 **/
class EncryptedCredentials extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['data', 'hash', 'secret'];
    }
}
