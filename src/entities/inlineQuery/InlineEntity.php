<?php
namespace onix\telegram\entities\inlineQuery;

use onix\telegram\entities\Entity;
use onix\telegram\entities\InlineKeyboard;
use onix\telegram\entities\inputMediaContent\InputMessageContent;

/**
 * Class InlineEntity
 *
 * This is the base class for all inline entities.
 *
 * @property-read string $type Type of the result, must be article
 * @property string $id Unique identifier for this result, 1-64 Bytes
 * @property InputMessageContent $inputMessageContent Content of the message to be sent
 * @property InlineKeyboard $replyMarkup Optional. Inline keyboard attached to the message
 */
abstract class InlineEntity extends Entity implements InlineQueryResult
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return [
            'type',
            'id',
            'inputMessageContent',
            'replyMarkup',
        ];
    }
}
