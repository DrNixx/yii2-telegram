<?php
namespace onix\telegram\entities;

use onix\telegram\entities\chatBoost\ChatBoostRemoved;
use onix\telegram\entities\chatBoost\ChatBoostUpdated;
use onix\telegram\entities\payments\PreCheckoutQuery;
use onix\telegram\entities\payments\ShippingQuery;
use onix\telegram\entities\reaction\MessageReactionCountUpdated;
use onix\telegram\entities\reaction\MessageReactionUpdated;

/**
 * Class Update
 *
 * @link https://core.telegram.org/bots/api#update
 *
 * @property-read int $updateId The update's unique identifier. Update identifiers start from a certain positive number
 * and increase sequentially. This ID becomes especially handy if you’re using Webhooks, since it allows you to ignore
 * repeated updates or to restore the correct update sequence, should they get out of order.
 *
 * @property-read Message $message Optional. New incoming message of any kind — text, photo, sticker, etc.
 * @property-read EditedMessage $editedMessage Optional.
 * New version of a message that is known to the bot and was edited
 *
 * @property-read ChannelPost $channelPost Optional. New post in the channel,
 * can be any kind — text, photo, sticker, etc.
 *
 * @property-read EditedChannelPost $editedChannelPost Optional. New version of a post in the channel that is known to
 * the bot and was edited
 *
 * @property-read MessageReactionUpdated $messageReaction Optional. A reaction to a message was changed by a user.
 * The bot must be an administrator in the chat and must explicitly specify "message_reaction" in the list
 * of allowed_updates to receive these updates. The update isn't received for reactions set by bots.
 *
 * @property-read MessageReactionCountUpdated $messageReactionCount Optional. Reactions to a message with anonymous
 * reactions were changed. The bot must be an administrator in the chat and must explicitly specify
 * "message_reaction_count" in the list of allowed_updates to receive these updates. The updates are grouped
 * and can be sent with delay up to a few minutes.
 *
 * @property-read InlineQuery $inlineQuery Optional. New incoming inline query
 * @property-read ChosenInlineResult $chosenInlineResult Optional. The result of an inline query that was chosen
 * by a user and sent to their chat partner.
 *
 * @property-read CallbackQuery $callbackQuery Optional. New incoming callback query
 * @property-read ShippingQuery $shippingQuery Optional. New incoming shipping query.
 * Only for invoices with flexible price
 *
 * @property-read PreCheckoutQuery $preCheckoutQuery Optional. New incoming pre-checkout query.
 * Contains full information about checkout
 *
 * @property-read Poll $poll Optional. New poll state. Bots receive only updates about polls,
 * which are sent or stopped by the bot
 *
 * @property-read PollAnswer $pollAnswer Optional. A user changed their answer in a non-anonymous poll.
 * Bots receive new votes only in polls that were sent by the bot itself.
 *
 * @property-read ChatMemberUpdated $myChatMember Optional. The bot's chat member status was updated in a chat.
 * For private chats, this update is received only when the bot is blocked or unblocked by the user.
 *
 * @property-read ChatMemberUpdated $chatMember Optional. A chat member's status was updated in a chat.
 * The bot must be an administrator in the chat and must explicitly specify “chat_member” in the list of
 * allowed_updates to receive these updates.
 *
 * @property-read ChatJoinRequest $chatJoinRequest Optional. A request to join the chat has been sent. The bot
 * must have the can_invite_users administrator right in the chat to receive these updates.
 *
 * @property-read ChatBoostUpdated $chatBoost Optional. A chat boost was added or changed. The bot must be
 * an administrator in the chat to receive these updates.
 *
 * @property-read ChatBoostRemoved $removedChatBoost Optional. A boost was removed from a chat. The bot must be
 * an administrator in the chat to receive these updates.
 */
class Update extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'updateId',
            'message',
            'editedMessage',
            'channelPost',
            'editedChannelPost',
            'messageReaction',
            'messageReactionCount',
            'inlineQuery',
            'chosenInlineResult',
            'callbackQuery',
            'shippingQuery',
            'preCheckoutQuery',
            'poll',
            'pollAnswer',
            'myChatMember',
            'chatMember',
            'chatJoinRequest',
            'chatBoost',
            'removedChatBoost',
        ];
    }
    
    /**
     * @inheritDoc
     */
    protected function subEntities(): array
    {
        return [
            'message' => Message::class,
            'editedMessage' => EditedMessage::class,
            'channelPost' => ChannelPost::class,
            'editedChannelPost' => EditedChannelPost::class,
            'messageReaction' => MessageReactionUpdated::class,
            'messageReactionCount' => MessageReactionCountUpdated::class,
            'inlineQuery' => InlineQuery::class,
            'chosenInlineResult' => ChosenInlineResult::class,
            'callbackQuery' => CallbackQuery::class,
            'shippingQuery' => ShippingQuery::class,
            'preCheckoutQuery' => PreCheckoutQuery::class,
            'poll' => Poll::class,
            'pollAnswer' => PollAnswer::class,
            'myChatMember' => ChatMemberUpdated::class,
            'chatMember' => ChatMemberUpdated::class,
            'chatJoinRequest' => ChatJoinRequest::class,
            'chatBoost' => ChatBoostUpdated::class,
            'removedChatBoost' => ChatBoostRemoved::class,
        ];
    }

    /**
     * Get the update type based on the set properties
     *
     * @return string|null
     */
    public function getUpdateType(): ?string
    {
        $types = array_keys($this->subEntities());
        foreach ($types as $type) {
            if ($this->getAttribute($type)) {
                return $type;
            }
        }

        return null;
    }

    /**
     * Get update content
     *
     * @return CallbackQuery|Message|InlineQuery|ChosenInlineResult|null
     */
    public function getUpdateContent(): CallbackQuery|Message|InlineQuery|ChosenInlineResult|null
    {
        if ($update_type = $this->getUpdateType()) {
            // Instead of just getting the property as an array,
            // use the __call method to get the correct Entity object.
            $method = 'get' . str_replace('_', '', ucwords($update_type, '_'));
            return $this->$method();
        }

        return null;
    }
}
