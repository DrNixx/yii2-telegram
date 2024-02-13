<?php

namespace onix\telegram\entities\giveaway;

use onix\telegram\entities\Entity;

/**
 * @link https://core.telegram.org/bots/api#giveawaywinners
 *
 */
class GiveawayWinners extends Entity
{
    /**
     * The chat that created the giveaway
     * @var Chat
     */
    public Chat $chat;

    /**
     * Identifier of the message with the giveaway in the chat
     * @var int
     */
    public int $giveaway_message_id;

    /**
     * Point in time (Unix timestamp) when winners of the giveaway were selected
     * @var int
     */
    public int $winners_selection_date;

    /**
     * Total number of winners in the giveaway
     * @var int
     */
    public int $winner_count;

    /**
     * List of up to 100 winners of the giveaway
     * @var User[]
     */
    #[ArrayType(User::class)]
    public array $winners;

    /**
     * Optional. The number of other chats the user had to join in order to be eligible for the giveaway
     * @var ?int
     */
    public ?int $additional_chat_count = null;

    /**
     * Optional. The number of months the Telegram Premium subscription won from the giveaway will be active for
     * @var ?int
     */
    public ?int $premium_subscription_month_count = null;

    /**
     * Optional. Number of undistributed prizes
     * @var ?int
     */
    public ?int $unclaimed_prize_count = null;

    /**
     * Optional. True, if only users who had joined the chats after the giveaway started were eligible to win
     * @var ?bool
     */
    public ?bool $only_new_members = null;

    /**
     * Optional. True, if the giveaway was canceled because the payment for it was refunded
     * @var ?bool
     */
    public ?bool $was_refunded = null;

    /**
     * Optional. Description of additional giveaway prize
     * @var ?string
     */
    public ?string $prize_description = null;
}
