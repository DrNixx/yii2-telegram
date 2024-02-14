<?php

namespace onix\telegram\entities;

/**
 * Represents a join request sent to a chat.
 *
 * @link https://core.telegram.org/bots/api#chatjoinrequest
 *
 * @property-read Chat $chat Chat the user belongs to
 * @property-read User $from Performer of the action, which resulted in the change
 *
 * @property-read int $userChatId Identifier of a private chat with the user who sent the join request.
 * This number may have more than 32 significant bits and some programming languages may have difficulty/silent
 * defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision
 * float type are safe for storing this identifier. The bot can use this identifier for 5 minutes to send messages
 * until the join request is processed, assuming no other administrator contacted the user.
 *
 * @property-read int $date Date the request was sent in Unix time
 * @property-read string $bio Optional. Bio of the user.
 * @property-read ChatInviteLink $inviteLink Optional. Chat invite link that was used by the user to send the join request
 */
class ChatJoinRequest extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'chat',
            'from',
            'userChatId',
            'date',
            'bio',
            'inviteLink',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities(): array
    {
        return [
            'chat' => Chat::class,
            'from' => User::class,
            'inviteLink' => ChatInviteLink::class,
        ];
    }
}