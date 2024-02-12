<?php

namespace onix\telegram\entities\chatMember;

use onix\telegram\entities\Entity;
use onix\telegram\entities\User;

/**
 * @link https://core.telegram.org/bots/api#chatmember
 *
 * @property-read string status The member's status in the chat
 * @property-read User user   Information about the user
 */
abstract class ChatMember extends Entity implements IChatMember
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['status', 'user'];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities(): array
    {
        return [
            'user' => User::class,
        ];
    }
}
