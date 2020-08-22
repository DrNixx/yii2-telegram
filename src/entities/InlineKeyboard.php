<?php
namespace onix\telegram\entities;

/**
 * Class InlineKeyboard
 *
 * @link https://core.telegram.org/bots/api#inlinekeyboardmarkup
 *
 * @property-read array $inlineKeyboard Array of button rows, each represented by an Array
 */
class InlineKeyboard extends Keyboard
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['resizeKeyboard', 'oneTimeKeyboard', 'selective', 'keyboard', 'inlineKeyboard'];
    }
}
