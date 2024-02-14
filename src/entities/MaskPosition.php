<?php
namespace onix\telegram\entities;

/**
 * Class MaskPosition
 *
 * @link https://core.telegram.org/bots/api#maskposition
 *
 * @property-read string $point The part of the face relative to which the mask should be placed.
 * One of "forehead", "eyes", "mouth", or "chin".
 *
 * @property-read float $xShift Shift by X-axis measured in widths of the mask scaled to the face size,
 * from left to right. For example, choosing -1.0 will place mask just to the left of the default mask position.
 *
 * @property-read float $yShift Shift by Y-axis measured in heights of the mask scaled to the face size, from
 * top to bottom. For example, 1.0 will place the mask just below the default mask position.
 *
 * @property-read float $scale Mask scaling coefficient. For example, 2.0 means double size.
 */
class MaskPosition extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['point', 'xShift', 'yShift', 'scale'];
    }
}
