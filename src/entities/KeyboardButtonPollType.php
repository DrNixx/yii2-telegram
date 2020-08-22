<?php
namespace onix\telegram\entities;

/**
 * Class KeyboardButtonPollType
 *
 * This entity represents type of a poll, which is allowed to be created and sent when
 * the corresponding button is pressed.
 *
 * @link https://core.telegram.org/bots/api#keyboardbutton
 *
 * @property string $type Optional. If 'quiz' is passed, the user will be allowed to create only polls in the
 * quiz mode. If 'regular' is passed, only regular polls will be allowed. Otherwise, the user will be
 * allowed to create a poll of any type.
 */
class KeyboardButtonPollType extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['type'];
    }
}
