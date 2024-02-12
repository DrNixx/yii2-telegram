<?php
namespace onix\telegram\entities;

/**
 * Class Audio
 *
 * @link https://core.telegram.org/bots/api#audio
 *
 * @property-read string $fileId Identifier for this file, which can be used to download or reuse the file
 * @property-read string $fileUniqueId Unique identifier for this file, which is supposed to be the same over time and
 * for different bots. Can't be used to download or reuse the file.
 *
 * @property-read int $duration Duration of the audio in seconds as defined by sender
 * @property-read string $performer Optional. Performer of the audio as defined by sender or by audio tags
 * @property-read string $title Optional. Title of the audio as defined by sender or by audio tags
 * @property-read string $fileName Optional. Original filename as defined by sender
 * @property-read string $mimeType Optional. MIME type of the file as defined by sender
 * @property-read int $fileSize Optional. File size
 * @property-read PhotoSize $thumb Optional. Thumbnail of the album cover to which the music file belongs
 */
class Audio extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['fileId', 'fileUniqueId', 'duration', 'performer', 'title', 'fileName', 'mimeType', 'fileSize', 'thumb'];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities(): array
    {
        return [
            'thumb' => PhotoSize::class,
        ];
    }
}
