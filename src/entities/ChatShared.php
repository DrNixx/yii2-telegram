<?php

namespace onix\telegram\entities;

/**
 * This object contains information about the chat whose identifier was shared with the bot using a KeyboardButtonRequestChat button.
 *
 * @link https://core.telegram.org/bots/api#chatshared
 *
 * @property-read int $requestId Identifier of the request
 * @property-read int $chatId Identifier of the shared chat.
 */
class ChatShared extends Entity
{
    public function attributes(): array
    {
        return ['requestId', 'chatId'];
    }
}
