<?php

namespace onix\telegram\entities\reaction;

use onix\telegram\entities\Chat;
use onix\telegram\entities\Entity;
use onix\telegram\entities\User;

/**
 * This object represents a change of a reaction on a message performed by a user.
 * @link https://core.telegram.org/bots/api#messagereactionupdated
 *
 * @property-read Chat $chat The chat containing the message the user reacted to
 * @property-read int $messageId Unique identifier of the message inside the chat
 * @property-read User|null $user Optional. The user that changed the reaction, if the user isn't anonymous
 * @property-read Chat|null $actorChat Optional. The chat on behalf of which the reaction was changed, if the user is anonymous
 * @property-read int $date Date of the change in Unix time
 * @property-read ReactionType[] $oldReaction Previous list of reaction types that were set by the user
 * @property-read ReactionType[] $newReaction New list of reaction types that have been set by the user
 */
class MessageReactionUpdated extends Entity
{
    public function attributes(): array
    {
        return ['chat', 'messageId', 'user', 'actorChat', 'date', 'oldReaction', 'newReaction'];
    }

    protected function subEntities(): array
    {
        return [
            'chat' => Chat::class,
            'user' => User::class,
            'actorChat' => Chat::class,
            'oldReaction' => [Factory::class],
            'newReaction' => [Factory::class],
        ];
    }
}
