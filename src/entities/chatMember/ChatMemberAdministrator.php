<?php

namespace onix\telegram\entities\chatMember;

use yii\helpers\ArrayHelper;

/**
 * Class ChatMemberAdministrator
 *
 * @link https://core.telegram.org/bots/api#chatmemberadministrator
 *
 * @property-read bool $canBeEdited Optional. Administrators only. True, if the bot is allowed to edit administrator
 *  privileges of that user
 *
 * @property-read bool $isAnonymous True, if the user's presence in the chat is hidden
 *
 * @property-read bool $canManageChat True, if the administrator can access the chat event log, chat statistics, message
 * statistics in channels, see channel members, see anonymous administrators in supergroups and ignore slow mode.
 * Implied by any other administrator privilege
 *
 * @property-read bool $canDeleteMessages True, if the administrator can delete messages of other users
 * @property-read bool $canManageVideoChats True, if the administrator can manage video chats
 * @property-read bool $canRestrictMembers True, if the administrator can restrict, ban or unban chat members
 * @property-read bool $canPromoteMembers True, if the administrator can add new administrators with a subset of their
 * own privileges or demote administrators that he has promoted, directly or indirectly (promoted by administrators
 * that were appointed by the user)
 *
 * @property-read bool $canChangeInfo True, if the user is allowed to change the chat title, photo and other settings
 * @property-read bool $canInviteUsers True, if the user is allowed to invite new users to the chat
 * @property-read bool $canPostMessages Optional. True, if the administrator can post in the channel; channels only
 * @property-read bool $canEditMessages Optional. True, if the administrator can edit messages of other users and can
 * pin messages; channels only
 *
 * @property-read bool $canPinMessages Optional. True, if the user is allowed to pin messages; groups and supergroups
 * only
 * @property-read bool $canManageTopics Optional. True, if the user is allowed to create, rename, close, and reopen
 * forum topics; supergroups only
 *
 * @property-read string $customTitle Custom title for this user
 */
class ChatMemberAdministrator extends ChatMember
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ArrayHelper::merge(parent::attributes(), [
            'canBeEdited',
            'isAnonymous',
            'canManageChat',
            'canDeleteMessages',
            'canManageVideoChats',
            'canRestrictMembers',
            'canPromoteMembers',
            'canChangeInfo',
            'canInviteUsers',
            'canPostMessages',
            'canEditMessages',
            'canPinMessages',
            'canManageTopics',
            'customTitle',
        ]);
    }
}
