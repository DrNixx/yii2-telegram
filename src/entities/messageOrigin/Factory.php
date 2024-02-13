<?php

namespace onix\telegram\entities\messageOrigin;

use onix\telegram\entities\Entity;

class Factory extends \onix\telegram\entities\Factory
{
    public static function make(array $data, string $bot_username): Entity
    {
        $type = [
            'user' => MessageOriginUser::class,
            'hidden_user' => MessageOriginHiddenUser::class,
            'chat' => MessageOriginChat::class,
            'channel' => MessageOriginChannel::class,
        ];

        if (!isset($type[$data['type'] ?? ''])) {
            $class = (new class extends MessageOrigin {})::class;
        } else {
            $class = $type[$data['type']];
        }

        return new $class($data, $bot_username);
    }
}
