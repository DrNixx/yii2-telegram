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
 * @property-read string $type Type of the sticker, currently one of “regular”, “mask”, “custom_emoji”.
 * The type of the sticker is independent from its format, which is determined by the fields is_animated and is_video.
 *
 * @property-read int $width Sticker width
 * @property-read int $height Sticker height
 * @property-read bool $isAnimated True, if the sticker is animated
 * @property-read bool $isVideo True, if the sticker is a video sticker
 * @property-read PhotoSize $thumbnail Optional. Sticker thumbnail in .webp or .jpg format
 * @property-read string $emoji Optional. Emoji associated with the sticker
 * @property-read string $setName Optional. Name of the sticker set to which the sticker belongs
 * @property-read MaskPosition $maskPosition Optional. For mask stickers, the position where the mask should be placed
 * @property-read string $customEmojiId Optional. For custom emoji stickers, unique identifier of the custom emoji
 * @property-read bool $needsRepainting Optional. True, if the sticker must be repainted to a text color in messages,
 * the color of the Telegram Premium badge in emoji status, white color on chat photos,
 * or another appropriate color in other places
 *
 * @property-read int $fileSize Optional. File size
 */
class Sticker extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'fileId',
            'fileUniqueId',
            'type',
            'width',
            'height',
            'isAnimated',
            'isVideo',
            'thumb',
            'thumbnail',
            'emoji',
            'setName',
            'maskPosition',
            'customEmojiId',
            'needsRepainting',
            'fileSize'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities(): array
    {
        return [
            'thumb' => PhotoSize::class,
            'maskPosition' => MaskPosition::class,
        ];
    }
}
