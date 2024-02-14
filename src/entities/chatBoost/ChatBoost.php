<?php

namespace onix\telegram\entities\chatBoost;

use onix\telegram\entities\chatBoost\Factory as ChatBoostSourceFactory;
use onix\telegram\entities\Entity;

/**
 * This object contains information about a chat boost.
 * @link https://core.telegram.org/bots/api#chatboost
 *
 * @property-read string $boostId Unique identifier of the boost
 * @property-read int $addDate Point in time (Unix timestamp) when the chat was boosted
 * @property-read int $expirationDate Point in time (Unix timestamp) when the boost will automatically expire, unless
 * the booster's Telegram Premium subscription is prolonged
 *
 * @property-read ChatBoostSource $source Source of the added boost
 */
class ChatBoost extends Entity
{
    public function attributes(): array
    {
        return [
            'boostId',
            'addDate',
            'expirationDate',
            'source'
        ];
    }

    protected function subEntities(): array
    {
        return [
            'source' => ChatBoostSourceFactory::class,
        ];
    }
}