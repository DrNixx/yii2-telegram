<?php

namespace onix\telegram\entities\giveaway;

use onix\telegram\entities\Entity;
use onix\telegram\entities\Message;

/**
 * This object represents a service message about the completion of a giveaway without public winners.
 * @link https://core.telegram.org/bots/api#giveawaycompleted
 *
 * @property-read int $winnerCount Number of winners in the giveaway
 * @property-read int $unclaimedPrizeCount Optional. Number of undistributed prizes
 * @property-read Message $giveawayMessage Optional. Message with the giveaway that was completed, if it wasn't deleted
 *
 */
class GiveawayCompleted extends Entity
{
    public function attributes(): array
    {
        return ['winnerCount', 'unclaimedPrizeCount', 'giveawayMessage'];
    }

    protected function subEntities(): array
    {
        return [
            'giveawayMessage' => Message::class,
        ];
    }
}
