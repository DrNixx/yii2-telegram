<?php
namespace onix\telegram\entities;

/**
 * Class PhotoSize
 *
 * @link https://core.telegram.org/bots/api#photosize
 *
 * @property-read string $fileId Identifier for this file, which can be used to download or reuse the file
 * @property-read string $fileUniqueId Unique identifier for this file, which is supposed to be the same over
 * time and for different bots. Can't be used to download or reuse the file.
 *
 * @property-read int $width Photo width
 * @property-read int $height Photo height
 * @property-read int $fileSize Optional. File size
 */
class PhotoSize extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['fileId', 'fileUniqueId', 'width', 'height', 'fileSize'];
    }
}
