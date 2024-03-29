<?php
namespace onix\telegram\entities;

/**
 * Class VoiceChatParticipantsInvited
 *
 * Represents a service message about new members invited to a voice chat
 *
 * @link https://core.telegram.org/bots/api#voicechatparticipantsinvited
 *
 * @property-read User[] $users Optional. New members that were invited to the voice chat
 */
class VideoChatParticipantsInvited extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'users'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities(): array
    {
        return [
            'users' => [User::class],
        ];
    }
}