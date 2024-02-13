<?php

namespace onix\telegram\entities\chatBoost;

use onix\telegram\entities\Entity;
use onix\telegram\entities\User;

/**
 * This object describes the source of a chat boost. It can be one of
 *  - {@see ChatBoostSourcePremium}
 *  - {@see ChatBoostSourceGiftCode}
 *  - {@see ChatBoostSourceGiveaway}
 * @link  https://core.telegram.org/bots/api#chatboostsource
 *
 * @property-read string $source Source of the boost
 * @property-read User $user User that boosted the chat; User for which the gift code was created; Optional. User that
 * won the prize in the giveaway if any;
 */
class ChatBoostSource extends Entity
{
    public function attributes(): array
    {
        return [
            'source',
            'user'
        ];
    }

    protected function subEntities(): array
    {
        return [
            'user' => User::class,
        ];
    }
}