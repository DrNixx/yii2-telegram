<?php

namespace onix\telegram\entities\messageOrigin;

use onix\telegram\entities\Chat;
use yii\helpers\ArrayHelper;

/**
 * The message was originally sent on behalf of a chat to a group chat.
 * @link https://core.telegram.org/bots/api#messageoriginchat
 *
 * @property-read int $date Date the message was sent originally in Unix time
 * @property-read Chat $senderChat Chat that sent the message originally
 * @property-read string $authorSignature Optional. For messages originally sent by an anonymous chat administrator,
 * original message author signature
 */
class MessageOriginChat extends MessageOrigin
{
    public function __construct($config)
    {
        $config['type'] = 'chat';
        parent::__construct($config);
    }

    public function attributes(): array
    {
        return ArrayHelper::merge(parent::attributes(), ['date', 'senderChat', 'authorSignature']);
    }

    protected function subEntities(): array
    {
        return [
            'senderChat' => Chat::class,
        ];
    }
}
