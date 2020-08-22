<?php
namespace onix\telegram\entities;

/**
 * Class Document
 *
 * @link https://core.telegram.org/bots/api#document
 *
 * @property-read string $fileId Identifier for this file, which can be used to download or reuse the file
 * @property-read string $fileUniqueId Unique identifier for this file, which is supposed to be the same over
 * time and for different bots. Can't be used to download or reuse the file.
 *
 * @property-read PhotoSize $thumb Optional. Document thumbnail as defined by sender
 * @property-read string $fileName Optional. Original filename as defined by sender
 * @property-read string $mimeType Optional. MIME type of the file as defined by sender
 * @property-read int $fileSize Optional. File size
 */
class Document extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['fileId', 'fileUniqueId', 'thumb', 'fileName', 'mimeType', 'fileSize'];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'thumb' => PhotoSize::class,
        ];
    }
}
