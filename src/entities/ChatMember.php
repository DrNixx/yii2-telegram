<?php
namespace onix\telegram\entities;

/**
 * Class ChatMember
 *
 * @link https://core.telegram.org/bots/api#chatmember
 *
 * @property-read User $user Information about the user
 * @property-read string $status The member's status in the chat. Can be "creator", "administrator", "member",
 * "restricted", "left" or "kicked"
 *
 * @property-read string $customTitle Optional. Owner and administrators only. Custom title for this user
 * @property-read int $untilDate Optional. Restricted and kicked only. Date when restrictions will be lifted for
 * this user, unix time
 *
 * @property-read bool $canBeEdited Optional. Administrators only. True, if the bot is allowed to edit administrator
 * privileges of that user
 *
 * @property-read bool $canPostMessages Optional. Administrators only. True, if the administrator can post in
 * the channel, channels only
 *
 * @property-read bool $canEditMessages Optional. Administrators only. True, if the administrator can edit messages of
 * other users, channels only
 *
 * @property-read bool $canDeleteMessages Optional. Administrators only. True, if the administrator can delete
 * messages of other users
 *
 * @property-read bool $canRestrictMembers Optional. Administrators only. True, if the administrator can restrict,
 * ban or unban chat members
 *
 * @property-read bool $canPromoteMembers Optional. Administrators only. True, if the administrator can add
 * new administrators with a subset of his own privileges or demote administrators that he has promoted, directly or
 * indirectly (promoted by administrators that were appointed by the user)
 *
 * @property-read bool $canChangeInfo Optional. Administrators and restricted only. True, if the user is allowed
 * to change the chat title, photo and other settings
 *
 * @property-read bool $canInviteUsers Optional. Administrators and restricted only. True, if the user is allowed
 * to invite new users to the chat
 *
 * @property-read bool $canPinMessages Optional. Administrators and restricted only. True, if the user is allowed to pin
 * messages; groups and supergroups only
 *
 * @property-read bool $isMember Optional. Restricted only. True, if the user is a member of the chat at
 * the moment of the request
 *
 * @property-read bool $canSendMessages Optional. Restricted only. True, if the user can send text messages,
 * contacts, locations and venues
 *
 * @property-read bool $canSendMediaMessages Optional. Restricted only. True, if the user can send audios, documents,
 * photos, videos, video notes and voice notes, implies can_send_messages
 *
 * @property-read bool $canSendPolls Optional. Restricted only. True, if the user is allowed to send polls
 * @property-read bool $canSendOtherMessages Optional. Restricted only. True, if the user can send animations, games,
 * stickers and use inline bots, implies can_send_media_messages
 *
 * @property-read bool $canAddWebPagePreviews Optional. Restricted only. True, if user may add web page previews
 * to his messages, implies can_send_media_messages
 */
class ChatMember extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'user',
            'status',
            'customTitle',
            'untilDate',
            'canBeEdited',
            'canPostMessages',
            'canEditMessages',
            'canDeleteMessages',
            'canRestrictMembers',
            'canPromoteMembers',
            'canChangeInfo',
            'canInviteUsers',
            'canPinMessages',
            'isMember',
            'canSendMessages',
            'canSendMediaMessages',
            'canSendPolls',
            'canSendOtherMessages',
            'canAddWebPagePreviews',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'user' => User::class,
        ];
    }
}
