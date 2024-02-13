<?php

namespace onix\telegram\entities\chatBoost;

use onix\telegram\entities\Chat;
use onix\telegram\entities\Entity;

/**
 * This object represents a boost added to a chat or changed.
 * @link https://core.telegram.org/bots/api#chatboostupdated
 *
 * @property-read Chat $chat Chat which was boosted
 * @property-read ChatBoost $boost Information about the chat boost
 */
class ChatBoostUpdated extends Entity
{
    public function attributes(): array
    {
        return [
            'chat',
            'boost'
        ];
    }

    protected function subEntities(): array
    {
        return [
            'chat' => Chat::class,
            'boost' => ChatBoost::class
        ];
    }
}