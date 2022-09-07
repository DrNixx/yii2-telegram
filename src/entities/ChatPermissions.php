<?php
namespace onix\telegram\entities;

/**
 * Class ChatPermissions
 *
 * @link https://core.telegram.org/bots/api#chatpermissions
 *
 * @property-read bool canSendMessages Optional. True, if the user is allowed to send text messages, contacts,
 * locations and venues
 *
 * @property-read bool canSendMediaMessages Optional. True, if the user is allowed to send audios, documents,
 * photos, videos, video notes and voice notes, implies can_send_messages
 *
 * @property-read bool $canSendPolls Optional. True, if the user is allowed to send polls, implies can_send_messages
 * @property-read bool $canSendOtherMessages Optional. True, if the user is allowed to send animations, games,
 * stickers and use inline bots, implies can_send_media_messages
 *
 * @property-read bool $canAddWebPagePreviews Optional. True, if the user is allowed to add web page previews to their
 * messages, implies can_send_media_messages
 *
 * @property-read bool $canChangeInfo Optional. True, if the user is allowed to change the chat title, photo and other
 * settings. Ignored in public supergroups
 *
 * @property-read bool $canInviteUsers Optional. True, if the user is allowed to invite new users to the chat
 * @property-read bool $canPinMessages Optional. True, if the user is allowed to pin messages.
 * Ignored in public supergroups
 */
class ChatPermissions extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'canSendMessages',
            'canSendMediaMessages',
            'canSendPolls',
            'canSendOtherMessages',
            'canAddWebPagePreviews',
            'canChangeInfo',
            'canInviteUsers',
            'canPinMessages'
        ];
    }
}
