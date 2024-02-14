<?php

namespace onix\telegram\entities\giveaway;

use onix\telegram\entities\Chat;
use onix\telegram\entities\Entity;

/**
 * This object represents a message about a scheduled giveaway.
 * @link https://core.telegram.org/bots/api#giveaway
 *
 * @property-read Chat[] $chats The list of chats which the user must join to participate in the giveaway
 * @property-read int $winnersSelectionDate Point in time (Unix timestamp) when winners of the giveaway will be selected
 * @property-read int $winnerCount The number of users which are supposed to be selected as winners of the giveaway
 * @property-read bool $onlyNewMembers Optional. True, if only users who join the chats after the giveaway started should be eligible to win
 * @property-read bool $hasPublicWinners Optional. True, if the list of giveaway winners will be visible to everyone
 * @property-read string $prizeDescription Optional. Description of additional giveaway prize
 * @property-read string[] $countryCodes Optional. A list of two-letter ISO 3166-1 alpha-2 country codes indicating
 * the countries from which eligible users for the giveaway must come. If empty, then all users can participate
 * in the giveaway. Users with a phone number that was bought on Fragment can always participate in giveaways.
 *
 * @property-read int $premiumSubscriptionMonthCount Optional. The number of months the Telegram Premium subscription
 * won from the giveaway will be active for
 */
class Giveaway extends Entity
{
    public function attributes(): array
    {
        return [
            'chats',
            'winnersSelectionDate',
            'winnerCount',
            'onlyNewMembers',
            'hasPublicWinners',
            'prizeDescription',
            'countryCodes',
            'premiumSubscriptionMonthCount'
        ];
    }

    protected function subEntities(): array
    {
        return [
            'chats' => [Chat::class]
        ];
    }
}
