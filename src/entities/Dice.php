<?php
namespace onix\telegram\entities;

/**
 * Class Dice
 *
 * This entity represents a dice with random value from 1 to 6.
 *
 * @link https://core.telegram.org/bots/api#dice
 *
 * @property-read string $emoji Emoji on which the dice throw animation is based
 * @property-read int $value Value of the dice, 1-6
 */
class Dice extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['emoji', 'value'];
    }
}
