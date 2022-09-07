<?php
namespace onix\telegram\entities;

/**
 * Class VideoChatEnded
 *
 * Represents a service message about new members invited to a voice chat
 *
 * @link https://core.telegram.org/bots/api#videochatended
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