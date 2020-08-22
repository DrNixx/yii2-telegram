<?php
namespace onix\telegram\entities;

/**
 * Class Video
 *
 * @link https://core.telegram.org/bots/api#video
 *
 * @property-read string $fileId Identifier for this file, which can be used to download or reuse the file
 * @property-read string $fileUniqueId Unique identifier for this file, which is supposed to be the same over
 * time and for different bots. Can't be used to download or reuse the file.
 *
 * @property-read int $width Video width as defined by sender
 * @property-read int $height Video height as defined by sender
 * @property-read int $duration Duration of the video in seconds as defined by sender
 * @property-read PhotoSize $thumb Optional. Video thumbnail
 * @property-read string $mimeType Optional. Mime type of a file as defined by sender
 * @property-read int $fileSize Optional. File size
 */
class Video extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['fileId', 'fileUniqueId', 'width', 'height', 'duration', 'thumb', 'mimeType', 'fileSize'];
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
