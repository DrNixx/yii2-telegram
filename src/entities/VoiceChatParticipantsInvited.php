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
class VoiceChatParticipantsInvited extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return [
            'users'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'users' => [User::class],
        ];
    }
}