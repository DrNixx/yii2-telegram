<?php

namespace onix\telegram\entities\messageOrigin;

use yii\helpers\ArrayHelper;

/**
 * The message was originally sent by an unknown user.
 * @link https://core.telegram.org/bots/api#messageoriginhiddenuser
 *
 * @property-read int $date Date the message was sent originally in Unix time
 * @property-read string $senderUserName Name of the user that sent the message originally
 */
class MessageOriginHiddenUser extends MessageOrigin
{
    public function __construct($config)
    {
        $config['type'] = 'hidden_user';
        parent::__construct($config);
    }

    public function attributes(): array
    {
        return ArrayHelper::merge(parent::attributes(), ['date', 'senderUserName']);
    }
}
