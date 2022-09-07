<?php
namespace onix\telegram\entities;

/**
 * Class Animation
 *
 * You can provide an animation for your game so that it looks stylish in chats (check out Lumberjack for an example).
 * This object represents an animation file to be displayed in the message containing a game.
 *
 * @link https://core.telegram.org/bots/api#animation
 *
 * @property-read  string $fileId Identifier for this file, which can be used to download or reuse the file
 * @property-read string $fileUniqueId Unique identifier for this file, which is supposed to be the same over time
 * and for different bots. Can't be used to download or reuse the file.
 *
 * @property-read int $width Video width as defined by sender
 * @property-read int $height Video height as defined by sender
 * @property-read int $duration Duration of the video in seconds as defined by sender
 * @property-read PhotoSize $thumb Optional. Animation thumbnail as defined by sender
 * @property-read string $fileName Optional. Original animation filename as defined by sender
 * @property-read string $mimeType Optional. MIME type of the file as defined by sender
 * @property-read int $fileSize Optional. File size
 **/
class Animation extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['fileId', 'fileUniqueId', 'width', 'height', 'duration', 'thumb', 'fileName', 'mimeType', 'fileSize'];
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
