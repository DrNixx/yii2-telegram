<?php

namespace onix\telegram\entities\reaction;

use onix\telegram\entities\Entity;

class Factory extends \onix\telegram\entities\Factory
{
    public static function make(array $data, string $bot_username): Entity
    {
        $type = [
            'emoji' => ReactionTypeEmoji::class,
            'custom_emoji' => ReactionTypeCustomEmoji::class,
        ];

        if (!isset($type[$data['type'] ?? ''])) {
            $class = (new class extends ReactionType {})::class;
        } else {
            $class = $type[$data['type']];
        }

        return new $class($data, $bot_username);
    }
}
