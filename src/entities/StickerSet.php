<?php
namespace onix\telegram\entities;

/**
 * Class StickerSet
 *
 * @link https://core.telegram.org/bots/api#stickerset
 *
 * @property-read string $name Sticker set name
 * @property-read string $title Sticker set title
 * @property-read bool $isAnimated True, if the sticker set contains animated stickers
 * @property-read bool $containsMasks True, if the sticker set contains masks
 * @property-read Sticker[] $stickers List of all set stickers
 * @property-read PhotoSize $thumb Optional. Sticker set thumbnail in the .WEBP or .TGS format
 */
class StickerSet extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['name', 'title', 'isAnimated', 'containsMasks', 'stickers', 'thumb'];
    }
    
    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'stickers' => [Sticker::class],
            'thumb' => PhotoSize::class,
        ];
    }
}
