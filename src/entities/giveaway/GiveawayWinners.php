<?php

namespace onix\telegram\entities\giveaway;

use onix\telegram\entities\Chat;
use onix\telegram\entities\Entity;
use onix\telegram\entities\User;

/**
 * @link https://core.telegram.org/bots/api#giveawaywinners
 *
 * @property-read Chat $chat The chat that created the giveaway
 * @property-read int $giveawayMessageId Identifier of the message with the giveaway in the chat
 * @property-read int $winnersSelectionDate Point in time (Unix timestamp) when winners of the giveaway were selected
 * @property-read int $winnerCount Total number of winners in the giveaway
 * @property-read User[] $winners List of up to 100 winners of the giveaway
 * @property-read int $additionalChatCount Optional. The number of other chats the user had to join in order to be eligible for the giveaway
 * @property-read int $premiumSubscriptionMonthCount Optional. The number of months the Telegram Premium subscription won from the giveaway will be active for
 * @property-read int $unclaimedPrizeCount Optional. Number of undistributed prizes
 * @property-read bool $onlyNewMembers Optional. True, if only users who had joined the chats after the giveaway started were eligible to win
 * @property-read bool $wasRefunded Optional. True, if the giveaway was canceled because the payment for it was refunded
 * @property-read string $prizeDescription Optional. Description of additional giveaway prize
 */
class GiveawayWinners extends Entity
{
    public function attributes(): array
    {
        return [
            'chat',
            'giveawayMessageId',
            'winnersSelectionDate',
            'winnerCount',
            'winners',
            'additionalChatCount',
            'premiumSubscriptionMonthCount',
            'unclaimedPrizeCount',
            'onlyNewMembers',
            'wasRefunded',
            'prizeDescription',
        ];
    }

    protected function subEntities(): array
    {
        return [
            'chat' => Chat::class,
            'winners' => [User::class],
        ];
    }
}
