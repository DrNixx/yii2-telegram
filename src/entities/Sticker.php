<?php
namespace onix\telegram\entities;

/**
 * Class Sticker
 *
 * @link https://core.telegram.org/bots/api#sticker
 *
 * @property-read string $fileId Identifier for this file, which can be used to download or reuse the file
 * @property-read string $fileUniqueId Unique identifier for this file, which is supposed to be the same over time
 * and for different bots. Can't be used to download or reuse the file.
 *
 * @property-read int $width Sticker width
 * @property-read int $height Sticker height
 * @property-read bool $isAnimated True, if the sticker is animated
 * @property-read bool $isVideo True, if the sticker is a video sticker
 * @property-read PhotoSize $thumb Optional. Sticker thumbnail in .webp or .jpg format
 * @property-read string $emoji Optional. Emoji associated with the sticker
 * @property-read string $setName Optional. Name of the sticker set to which the sticker belongs
 * @property-read MaskPosition $maskPosition Optional. For mask stickers, the position where the mask should be placed
 * @property-read int $fileSize Optional. File size
 */
class Sticker extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return [
            'fileId',
            'fileUniqueId',
            'width',
            'height',
            'isAnimated',
            'isVideo',
            'thumb',
            'emoji',
            'setName',
            'maskPosition',
            'fileSize'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'thumb' => PhotoSize::class,
            'maskPosition' => MaskPosition::class,
        ];
    }
}
