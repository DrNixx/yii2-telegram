<?php

namespace onix\telegram\entities\chatBoost;

/**
 * The boost was obtained by the creation of Telegram Premium gift codes to boost a chat.
 * Each such code boosts the chat 4 times for the duration of the corresponding Telegram Premium subscription.
 * @link https://core.telegram.org/bots/api#chatboostsourcegiftcode
 */
class ChatBoostSourceGiftCode extends ChatBoostSource
{
    public function __construct($config)
    {
        $config['source'] = 'gift_code';
        parent::__construct($config);
    }
}