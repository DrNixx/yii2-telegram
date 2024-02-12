<?php
namespace onix\telegram\entities;

/**
 * Class CallbackQuery.
 *
 * @link https://core.telegram.org/bots/api#callbackquery
 *
 * @property-read string $id Unique identifier for this query
 * @property-read User $from Sender
 * @property-read Message|EditedMessage $message Optional. Message with the callback button that originated the query.
 * Note that message content and message date will not be available if the message is too old
 *
 * @property-read string $inlineMessageId Optional. Identifier of the message sent via the bot in inline mode,
 * that originated the query
 *
 * @property-read string $chatInstance Global identifier, uniquely corresponding to the chat to which the message
 * with the callback button was sent. Useful for high scores in games.
 *
 * @property-read string $data Data associated with the callback button. Be aware that a bad client can send
 * arbitrary data in this field
 *
 * @property-read string $gameShortName Optional. Short name of a Game to be returned, serves as the unique
 * identifier for the game
 */
class CallbackQuery extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['id', 'from', 'message', 'inlineMessageId', 'chatInstance', 'data', 'gameShortName'];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities(): array
    {
        return [
            'from' => User::class,
            'message' => Message::class,
        ];
    }

    /**
     * Answer this callback query.
     *
     * @param array $data
     *
     * @return ServerResponse
     */
    public function answer(array $data = []): ServerResponse
    {
        return $this->telegram->request->answerCallbackQuery(array_merge([
            'callback_query_id' => $this->id,
        ], $data));
    }
}
