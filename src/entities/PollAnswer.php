<?php
namespace onix\telegram\entities;

/**
 * Class PollAnswer
 *
 * This entity represents an answer of a user in a non-anonymous poll.
 *
 * @link https://core.telegram.org/bots/api#pollanswer
 *
 * @property-read string $pollId Unique poll identifier
 * @property-read User $user The user, who changed the answer to the poll
 * @property-read array $optionIds 0-based identifiers of answer options, chosen by the user.
 * May be empty if the user retracted their vote.
 */
class PollAnswer extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['pollId', 'user', 'optionIds'];
    }
    
    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'user' => User::class,
        ];
    }
}
