<?php

namespace onix\telegram\entities\reaction;

use onix\telegram\entities\Entity;

/**
 * Represents a reaction added to a message along with the number of times it was added.
 * @link https://core.telegram.org/bots/api#reactioncount
 *
 * @property-read ReactionType $type Type of the reaction
 * @property-read int $totalCount Number of times the reaction was added
 */
class ReactionCount extends Entity
{
    public function attributes(): array
    {
        return ['type', 'totalCount'];
    }

    protected function subEntities(): array
    {
        return [
            'type' => Factory::class
        ];
    }
}
