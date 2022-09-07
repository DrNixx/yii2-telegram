<?php
namespace onix\telegram\entities;

use onix\telegram\entities\inlineQuery\InlineQueryResult;

/**
 * Class InlineQuery
 *
 * @link https://core.telegram.org/bots/api#inlinequery
 *
 * @property-read string $id Unique identifier for this query
 * @property-read User $from Sender
 * @property-read Location $location Optional. Sender location, only for bots that request user location
 * @property-read string $query Text of the query (up to 512 characters)
 * @property-read string $offset Offset of the results to be returned, can be controlled by the bot
 */
class InlineQuery extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['id', 'from', 'location', 'query', 'offset'];
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

    /**
     * Answer this inline query with the passed results.
     *
     * @param InlineQueryResult[] $results
     * @param array               $data
     *
     * @return ServerResponse
     */
    public function answer(array $results, array $data = [])
    {
        return $this->telegram->request->answerInlineQuery(array_merge([
            'inline_query_id' => $this->id,
            'results' => $results,
        ], $data));
    }
}
