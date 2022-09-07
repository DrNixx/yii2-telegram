<?php

namespace onix\telegram\entities;

/**
 * Class VideoChatScheduled
 *
 * This object represents a service message about a video chat scheduled in the chat.
 *
 * @link https://core.telegram.org/bots/api#videochatscheduled
 *
 * @property-read int $startDate Point in time (Unix timestamp) when the video chat is supposed to be started
 * by a chat administrator
 */
class VideoChatScheduled extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'startDate'
        ];
    }
}