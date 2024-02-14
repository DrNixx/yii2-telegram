<?php

namespace onix\telegram\entities\messageOrigin;

use onix\telegram\entities\User;
use yii\helpers\ArrayHelper;

/**
 * The message was originally sent by a known user.
 * @link https://core.telegram.org/bots/api#messageoriginuser
 *
 * @property-read int $date Date the message was sent originally in Unix time
 * @property-read User $senderUser User that sent the message originally
 */
class MessageOriginUser extends MessageOrigin
{
    public function __construct($config)
    {
        $config['type'] = 'user';
        parent::__construct($config);
    }

    public function attributes(): array
    {
        return ArrayHelper::merge(parent::attributes(), ['date', 'senderUser']);
    }

    protected function subEntities(): array
    {
        return [
            'senderUser' => User::class,
        ];
    }
}
