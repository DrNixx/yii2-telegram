<?php
namespace onix\telegram\entities;

use onix\telegram\entities\payments\PreCheckoutQuery;
use onix\telegram\entities\payments\ShippingQuery;

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
 */
class Update extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return [
            'updateId',
            'message',
            'editedMessage',
            'channelPost',
            'editedChannelPost',
            'inlineQuery',
            'chosenInlineResult',
            'callbackQuery',
            'shippingQuery',
            'preCheckoutQuery',
            'poll',
            'pollAnswer'
        ];
    }
    
    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'message' => Message::class,
            'editedMessage' => EditedMessage::class,
            'channelPost' => ChannelPost::class,
            'editedChannelPost' => EditedChannelPost::class,
            'inlineQuery' => InlineQuery::class,
            'chosenInlineResult' => ChosenInlineResult::class,
            'callbackQuery' => CallbackQuery::class,
            'shippingQuery' => ShippingQuery::class,
            'preCheckoutQuery' => PreCheckoutQuery::class,
            'poll' => Poll::class,
            'pollAnswer' => PollAnswer::class,
        ];
    }

    /**
     * Get the update type based on the set properties
     *
     * @return string|null
     */
    public function getUpdateType()
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
     * @return CallbackQuery|ChosenInlineResult|InlineQuery|Message
     */
    public function getUpdateContent()
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
