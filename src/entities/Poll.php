<?php
namespace onix\telegram\entities;

/**
 * Class Poll
 *
 * This entity contains information about a poll.
 *
 * @link https://core.telegram.org/bots/api#poll
 *
 * @property-read string $id Unique poll identifier
 * @property-read string $question Poll question, 1-255 characters
 * @property-read PollOption[] $options List of poll options
 * @property-read int $totalVoterCount Total number of users that voted in the poll
 * @property-read bool $isClosed True, if the poll is closed
 * @property-read bool $isAnonymous True, if the poll is anonymous
 * @property-read string $type Poll type, currently can be "regular" or "quiz"
 * @property-read bool $allowsMultipleAnswers True, if the poll allows multiple answers
 * @property-read int $correctOptionId Optional. 0-based identifier of the correct answer option.
 * Available only for polls in the quiz mode, which are closed, or was sent (not forwarded) by the bot or to the
 * private chat with the bot.
 *
 * @property-read string $explanation Optional. Text that is shown when a user chooses an incorrect answer or taps on
 * the lamp icon in a quiz-style poll, 0-200 characters
 *
 * @property-read MessageEntity[] $explanationEntities Optional. Special entities like usernames, URLs, bot commands,
 * etc. that appear in the explanation
 *
 * @property-read int $openPeriod Optional. Amount of time in seconds the poll will be active after creation
 * @property-read int $closeDate Optional. Point in time (Unix timestamp) when the poll will be automatically closed
 */
class Poll extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'id',
            'question',
            'options',
            'totalVoterCount',
            'isClosed',
            'isAnonymous',
            'type',
            'allowsMultipleAnswers',
            'correctOptionId',
            'explanation',
            'explanationEntities',
            'openPeriod',
            'closeDate'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'options' => [PollOption::class],
            'explanationEntities' => [MessageEntity::class],
        ];
    }
}
