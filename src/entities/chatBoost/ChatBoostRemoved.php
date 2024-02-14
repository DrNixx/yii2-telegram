<?php

namespace onix\telegram\entities\chatBoost;

use onix\telegram\entities\Chat;
use onix\telegram\entities\chatBoost\Factory as ChatBoostSourceFactory;
use onix\telegram\entities\Entity;

/**
 * This object represents a list of boosts added to a chat by a user.
 * @link https://core.telegram.org/bots/api#userchatboosts
 *
 * @property-read Chat $chat Chat which was boosted
 * @property-read string $boostId Unique identifier of the boost
 * @property-read int $removeDate Point in time (Unix timestamp) when the boost was removed
 * @property-read ChatBoostSource $source Source of the removed boost
 */
class ChatBoostRemoved extends Entity
{
    public function attributes(): array
    {
        return [
            'chat',
            'boostId',
            'removeDate',
            'source'
        ];
    }

    protected function subEntities(): array
    {
        return [
            'chat' => Chat::class,
            'source' => ChatBoostSourceFactory::class,
        ];
    }
}