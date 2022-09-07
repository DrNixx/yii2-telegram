<?php
namespace onix\telegram\entities;

/**
 * Class ChatInviteLink
 *
 * Represents an invite link for a chat.
 *
 * @link https://core.telegram.org/bots/api#chatinvitelink
 *
 * @property-read string $inviteLink The invite link. If the link was created by another chat administrator, then the second part of the link will be replaced with “…”.
 * @property-read User $creator Creator of the link
 * @property-read bool $isPrimary True, if the link is primary
 * @property-read bool $isRevoked True, if the link is revoked
 * @property-read int $expireDate Optional. Point in time (Unix timestamp) when the link will expire or has been expired
 * @property-read int $memberLimit Optional. Maximum number of users that can be members of the chat simultaneously after joining the chat via this invite link; 1-99999
 */
class ChatInviteLink extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'inviteLink',
            'creator',
            'isPrimary',
            'isRevoked',
            'expireDate',
            'memberLimit',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'creator' => User::class,
        ];
    }
}