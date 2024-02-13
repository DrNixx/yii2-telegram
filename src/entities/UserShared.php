<?php

namespace onix\telegram\entities;

/**
 * This object contains information about the user whose identifier was shared with the bot using a KeyboardButtonRequestUser button.
 *
 * @link https://core.telegram.org/bots/api#usershared
 *
 * @property-read int $requestId Identifier of the request
 * @property-read int $userId Identifier of the shared user.
 */
class UserShared extends Entity
{
    public function attributes(): array
    {
        return ['requestId', 'userId'];
    }
}
