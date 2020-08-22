<?php
namespace onix\telegram\entities\passport;

use onix\telegram\entities\Entity;

/**
 * Class PassportFile
 *
 * This object represents a file uploaded to Telegram Passport. Currently all Telegram Passport files are
 * in JPEG format when decrypted and don't exceed 10MB.
 *
 * @link https://core.telegram.org/bots/api#passportfile
 *
 * @property-read string $fileId Identifier for this file, which can be used to download or reuse the file
 * @property-read string $fileUniqueId Unique identifier for this file, which is supposed to be the same over time and
 * for different bots. Can't be used to download or reuse the file.
 *
 * @property-read int $fileSize File size
 * @property-read int $fileDate Unix time when the file was uploaded
 **/
class PassportFile extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['fileId', 'fileUniqueId', 'fileSize', 'fileDate'];
    }
}
