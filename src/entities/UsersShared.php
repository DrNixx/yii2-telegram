<?php

namespace onix\telegram\entities;

/**
 * This object contains information about the user whose identifier was shared
 * with the bot using a {@see https://core.telegram.org/bots/api#keyboardbuttonrequestuser KeyboardButtonRequestUser} button.
 * @link https://core.telegram.org/bots/api#usersshared
 *
 * @property-read int $requestId Identifier of the request
 * @property-read int[] $userIds Identifiers of the shared users. These numbers may have more than 32 significant
 * bits and some programming languages may have difficulty/silent defects in interpreting them. But they have
 * at most 52 significant bits, so 64-bit integers or double-precision float types are safe for storing these
 * identifiers. The bot may not have access to the users and could be unable to use these identifiers, unless
 * the users are already known to the bot by some other means.
 */
class UsersShared extends Entity
{
    public function attributes(): array
    {
        return ['requestId', 'userIds'];
    }
}