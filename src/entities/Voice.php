<?php
namespace onix\telegram\entities;

/**
 * Class Voice
 *
 * @link https://core.telegram.org/bots/api#voice
 *
 * @property-read string $fileId Identifier for this file, which can be used to download or reuse the file
 * @property-read string $fileUniqueId Unique identifier for this file, which is supposed to be the same over time
 * and for different bots. Can't be used to download or reuse the file.
 *
 * @property-read int $duration Duration of the audio in seconds as defined by sender
 * @property-read string $mimeType Optional. MIME type of the file as defined by sender
 * @property-read int $fileSize Optional. File size
 */
class Voice extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['fileId', 'fileUniqueId', 'duration', 'mimeType', 'fileSize'];
    }
}
