<?php

namespace onix\telegram\entities\reaction;

use onix\telegram\entities\Chat;
use onix\telegram\entities\Entity;

/**
 * This object represents reaction changes on a message with anonymous reactions.
 * @link https://core.telegram.org/bots/api#messagereactioncountupdated
 *
 * @property-read Chat $chat The chat containing the message
 * @property-read int $messageId Unique message identifier inside the chat
 * @property-read int $date Date of the change in Unix time
 * @property-read ReactionCount[] $reactions List of reactions that are present on the message
 */
class MessageReactionCountUpdated extends Entity
{
    public function attributes(): array
    {
        return ['chat', 'messageId', 'date', 'reactions'];
    }

    protected function subEntities(): array
    {
        return [
            'chat' => Chat::class,
            'reactions' => [ReactionCount::class],
        ];
    }
}
