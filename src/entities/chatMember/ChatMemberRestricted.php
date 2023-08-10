<?php

namespace onix\telegram\entities\chatMember;

use yii\helpers\ArrayHelper;

/**
 * Class ChatMemberRestricted
 *
 * @link https://core.telegram.org/bots/api#chatmemberrestricted
 *
 * @property-read bool $isMember True, if the user is a member of the chat at the moment of the request
 * @property-read bool $canChangeInfo True, if the user is allowed to change the chat title, photo and other settings
 * @property-read bool $canInviteUsers True, if the user is allowed to invite new users to the chat
 * @property-read bool $canPinMessages True, if the user is allowed to pin messages; groups and supergroups only
 * @property-read bool $canManageTopics True, if the user is allowed to create forum topics
 * @property-read bool $canSendMessages True, if the user is allowed to send text messages, contacts, locations and venues
 * @property-read bool $canSendAudios True, if the user is allowed to send audios
 * @property-read bool $canSendDocuments True, if the user is allowed to send documents
 * @property-read bool $canSendPhotos True, if the user is allowed to send photos
 * @property-read bool $canSendVideos True, if the user is allowed to send videos
 * @property-read bool $canSendVideoNotes True, if the user is allowed to send video notes
 * @property-read bool $canSendVoiceNotes True, if the user is allowed to send voice notes
 * @property-read bool $canSendPolls True, if the user is allowed to send polls
 * @property-read bool $canSendOtherMessages True, if the user is allowed to send animations, games, stickers and use inline bots
 * @property-read bool $canAddWebPagePreviews True, if the user is allowed to add web page previews to their messages
 * @property-read int  $untilDate Date when restrictions will be lifted for this user; unix time
 */
class ChatMemberRestricted extends ChatMember
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'isMember',
            'canChangeInfo',
            'canInviteUsers',
            'canPinMessages',
            'canManageTopics',
            'canSendMessages',
            'canSendAudios',
            'canSendDocuments',
            'canSendPhotos',
            'canSendVideos',
            'canSendVideoNotes',
            'canSendVoiceNotes',
            'canSendPolls',
            'canSendOtherMessages',
            'canAddWebPagePreviews',
            'untilDate',
        ]);
    }

    /**
     * True, if the user is allowed to send audios, documents, photos, videos, video notes OR voice notes
     *
     * @deprecated Use new fine-grained methods provided by Telegram Bot API.
     *
     * @return bool
     */
    public function getCanSendMediaMessages(): bool
    {
        return $this->canSendAudios ||
            $this->canSendDocuments ||
            $this->canSendPhotos ||
            $this->canSendVideos ||
            $this->canSendVideoNotes ||
            $this->canSendVoiceNotes;
    }
}
