<?php

namespace onix\telegram\entities\chatBoost;

/**
 * The boost was obtained by subscribing to Telegram Premium or by gifting a Telegram Premium subscription to
 * another user.
 *
 * @link https://core.telegram.org/bots/api#chatboostsourcepremium
 */
class ChatBoostSourcePremium extends ChatBoostSource
{
    public function __construct($config)
    {
        $config['source'] = 'premium';
        parent::__construct($config);
    }
}