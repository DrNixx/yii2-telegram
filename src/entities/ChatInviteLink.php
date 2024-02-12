<?php
namespace onix\telegram\entities;

/**
 * Class ChatInviteLink
 *
 * Represents an invite link for a chat.
 *
 * @link https://core.telegram.org/bots/api#chatinvitelink
 *
 * @property-read string $inviteLink The invite link. If the link was created by another chat administrator, then
 * the second part of the link will be replaced with “…”.
 *
 * @property-read User $creator Creator of the link
 * @property-read bool $createsJoinRequest True, if users joining the chat via the link need to be approved by chat administrators
 * @property-read bool $isPrimary True, if the link is primary
 * @property-read bool $isRevoked True, if the link is revoked
 * @property-read string $name Optional. Invite link name
 * @property-read int $expireDate Optional. Point in time (Unix timestamp) when the link will expire or has been expired
 * @property-read int $memberLimit Optional. Maximum number of users that can be members of the chat simultaneously
 * after joining the chat via this invite link; 1-99999
 *
 * @property-read int $pendingJoinRequestCount Optional. Number of pending join requests created using this link
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
            'createsJoinRequest',
            'isPrimary',
            'isRevoked',
            'name',
            'expireDate',
            'memberLimit',
            'pendingJoinRequestCount',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities(): array
    {
        return [
            'creator' => User::class,
        ];
    }
}