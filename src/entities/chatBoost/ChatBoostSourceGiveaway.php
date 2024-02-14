<?php

namespace onix\telegram\entities\chatBoost;

use yii\helpers\ArrayHelper;

/**
 * The boost was obtained by the creation of a Telegram Premium giveaway. This boosts the chat 4 times for the
 * duration of the corresponding Telegram Premium subscription.
 *
 * @link https://core.telegram.org/bots/api#chatboostsourcegiveaway
 *
 * @property-read int $giveawayMessageId	Identifier of a message in the chat with the giveaway; the message could
 * have been deleted already. May be 0 if the message isn't sent yet.
 *
 * @property-read bool $isUnclaimed Optional. True, if the giveaway was completed, but there was no user to win the prize
 */
class ChatBoostSourceGiveaway extends ChatBoostSource
{
    public function attributes(): array
    {
        return ArrayHelper::merge(parent::attributes(), ['giveawayMessageId', 'isUnclaimed']);
    }

    public function __construct($config)
    {
        $config['source'] = 'giveaway';
        parent::__construct($config);
    }
}
