<?php

namespace onix\telegram\entities\messageOrigin;

use onix\telegram\entities\Chat;
use yii\helpers\ArrayHelper;

/**
 * The message was originally sent to a channel chat.
 * @link https://core.telegram.org/bots/api#messageoriginchannel
 *
 * @property-read int $date Date the message was sent originally in Unix time
 * @property-read Chat $chat Channel chat to which the message was originally sent
 * @property-read int $messageId Unique message identifier inside the chat
 * @property-read string $authorSignature Optional. Signature of the original post author
 */
class MessageOriginChannel extends MessageOrigin
{
    public function __construct($config)
    {
        $config['type'] = 'channel';
        parent::__construct($config);
    }

    public function attributes(): array
    {
        return ArrayHelper::merge(parent::attributes(), ['date', 'senderChat', 'messageId', 'authorSignature']);
    }

    protected function subEntities(): array
    {
        return [
            'senderChat' => Chat::class,
        ];
    }
}
