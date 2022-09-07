<?php
namespace onix\telegram\entities;

/**
 * Class VideoNote
 *
 * @link https://core.telegram.org/bots/api#videonote
 *
 * @property-read string $fileId Identifier for this file, which can be used to download or reuse the file
 * @property-read string $fileUniqueId Unique identifier for this file, which is supposed to be the same over
 * time and for different bots. Can't be used to download or reuse the file.
 *
 * @property-read int $length Video width and height as defined by sender
 * @property-read int $duration Duration of the audio in seconds as defined by sender
 * @property-read PhotoSize $thumb Optional. Video thumbnail as defined by sender
 * @property-read int $fileSize Optional. File size
 */
class VideoNote extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['fileId', 'fileUniqueId', 'length', 'duration', 'thumb', 'fileSize'];
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
