<?php
namespace onix\telegram\entities;

/**
 * Class File
 *
 * @link https://core.telegram.org/bots/api#file
 *
 * @property-read string $fileId Identifier for this file, which can be used to download or reuse the file
 * @property-read string $fileUniqueId Unique identifier for this file, which is supposed to be the same over time
 * and for different bots. Can't be used to download or reuse the file.
 *
 * @property-read int $fileSize Optional. File size, if known
 * @property-read string $filePath Optional. File path. Use https://api.telegram.org/file/bot<token>/<file_path>
 * to get the file.
 */
class File extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['fileId', 'fileUniqueId', 'fileSize', 'filePath'];
    }
}
