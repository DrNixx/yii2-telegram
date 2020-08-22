<?php
namespace onix\telegram\entities\passport;

use onix\telegram\entities\Entity;

/**
 * Class EncryptedPassportElement
 *
 * Contains information about documents or other Telegram Passport elements shared with the bot by the user.
 *
 * @link https://core.telegram.org/bots/api#encryptedpassportelement
 *
 * @property-read string $type Element type. One of "personal_details", "passport", "driver_license", "identity_card",
 * "internal_passport ", "address", "utility_bill", "bank_statement", "rental_agreement", "passport_registration",
 * "temporary_registration", "phone_number", "email".
 *
 * @property-read string $data Optional. Base64-encoded encrypted Telegram Passport element data provided by the user,
 * available for "personal_details", "passport", "driver_license", "identity_card", "identity_passport" and "address"
 * types. Can be decrypted and verified using the accompanying EncryptedCredentials.
 *
 * @property-read string $phoneNumber Optional. User's verified phone number, available only for "phone_number" type
 * @property-read string $email Optional. User's verified email address, available only for "email" type
 * @property-read PassportFile[] $files Optional. Array of encrypted files with documents provided by the user,
 * available for "utility_bill", "bank_statement", "rental_agreement", "passport_registration" and
 * "temporary_registration" types. Files can be decrypted and verified using the accompanying EncryptedCredentials.
 *
 * @property-read PassportFile $frontSide Optional. Encrypted file with the front side of the document,
 * provided by the user. Available for "passport", "driver_license", "identity_card" and "internal_passport".
 * The file can be decrypted and verified using the accompanying EncryptedCredentials.
 *
 * @property-read PassportFile $reverseSide Optional. Encrypted file with the reverse side of the document,
 * provided by the user. Available for "driver_license" and "identity_card". The file can be decrypted and verified
 * using the accompanying EncryptedCredentials.
 *
 * @property-read PassportFile $selfie Optional. Encrypted file with the selfie of the user holding a document,
 * provided by the user; available for "passport", "driver_license", "identity_card" and "internal_passport".
 * The file can be decrypted and verified using the accompanying EncryptedCredentials.
 *
 * @property-read PassportFile[] $translation Optional. Array of encrypted files with translated versions of documents
 * provided by the user. Available if requested for "passport", "driver_license", "identity_card", "internal_passport",
 * "utility_bill", "bank_statement", "rental_agreement", "passport_registration" and "temporary_registration" types.
 * Files can be decrypted and verified using the accompanying EncryptedCredentials.
 *
 * @property-read string $hash Base64-encoded element hash for using in PassportElementErrorUnspecified
 **/
class EncryptedPassportElement extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return [
            'type',
            'data',
            'phoneNumber',
            'email',
            'files',
            'frontSide',
            'reverseSide',
            'selfie',
            'translation',
            'hash'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'files' => [PassportFile::class],
            'frontSide' => PassportFile::class,
            'reverseSide' => PassportFile::class,
            'selfie' => PassportFile::class,
            'translation' => [PassportFile::class],
        ];
    }
}
