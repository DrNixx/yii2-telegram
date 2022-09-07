<?php
namespace onix\telegram\entities;

/**
 * Class ChosenInlineResult
 *
 * @link https://core.telegram.org/bots/api#choseninlineresult
 *
 * @property-read string $resultId The unique identifier for the result that was chosen
 * @property-read User $from The user that chose the result
 * @property-read Location $location Optional. Sender location, only for bots that require user location
 * @property-read string $inlineMessageId Optional. Identifier of the sent inline message. Available only if there
 * is an inline keyboard attached to the message. Will be also received in callback queries and can be used
 * to edit the message.
 *
 * @property-read string $query The query that was used to obtain the result
 */
class ChosenInlineResult extends Entity
{
    /**
     * @var int
     */
    public $chosen_inline_result_id;

    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['resultId', 'from', 'location', 'inlineMessageId', 'query'];
    }
    
    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'from' => User::class,
            'location' => Location::class,
        ];
    }
}
