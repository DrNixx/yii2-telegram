<?php

namespace onix\telegram\entities\chatBoost;

use onix\telegram\entities\Entity;

class Factory extends \onix\telegram\entities\Factory
{
    public static function make(array $data, string $bot_username): Entity
    {
        $type = [
            'premium' => ChatBoostSourcePremium::class,
            'gift_code' => ChatBoostSourceGiftCode::class,
            'giveaway' => ChatBoostSourceGiveaway::class,
        ];

        if (!isset($type[$data['source'] ?? ''])) {
            throw new \InvalidArgumentException('Type must be defined');
        }

        $class = $type[$data['source']];
        return new $class($data, $bot_username);
    }
}