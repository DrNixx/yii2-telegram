<?php
namespace onix\telegram\entities;

/**
 * Class PollOption
 *
 * This entity contains information about one answer option in a poll.
 *
 * @link https://core.telegram.org/bots/api#polloption
 *
 * @property-read string $text Option text, 1-100 characters
 * @property-read int $voterCount Number of users that voted for this option
 */
class PollOption extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['text', 'voterCount'];
    }
}
