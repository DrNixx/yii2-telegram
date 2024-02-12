<?php
namespace onix\telegram\entities;

/**
 * Class MessageAutoDeleteTimerChanged
 *
 * This object represents a service message about a change in auto-delete timer settings.
 *
 * @link https://core.telegram.org/bots/api#messageautodeletetimerchanged
 *
 * @property-read int $messageAutoDeleteTime New auto-delete time for messages in the chat
 */
class MessageAutoDeleteTimerChanged extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'messageAutoDeleteTime'
        ];
    }
}