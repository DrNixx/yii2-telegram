<?php
namespace onix\telegram\entities;

/**
 * Class VoiceChatEnded
 *
 * Represents a service message about new members invited to a voice chat
 *
 * @link https://core.telegram.org/bots/api#voicechatended
 *
 * @property-read int $duration Voice chat duration; in seconds
 */
class VideoChatEnded extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'duration'
        ];
    }
}