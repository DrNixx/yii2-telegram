<?php
namespace onix\telegram\models;

use onix\data\ActiveRecordEx;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "telegram.telegram_update".
 *
 * @property int $id Update's unique identifier
 * @property int|null $chat_id Unique chat identifier
 * @property int|null $message_id New incoming message of any kind - text, photo, sticker, etc.
 * @property int|null $edited_message_id New version of a message that is known to the bot and was edited
 * @property int|null $channel_post_id New incoming channel post of any kind - text, photo, sticker, etc.
 * @property int|null $edited_channel_post_id New version of a channel post that is known to the bot and was edited
 * @property int|null $inline_query_id New incoming inline query
 * @property int|null $chosen_inline_result_id The result of an inline query that was chosen by a user and sent
 * to their chat partner
 *
 * @property int|null $callback_query_id New incoming callback query
 * @property int|null $shipping_query_id New incoming shipping query. Only for invoices with flexible price
 * @property int|null $pre_checkout_query_id New incoming pre-checkout query. Contains full information about checkout
 * @property int|null $poll_id New poll state. Bots receive only updates about polls,
 * which are sent or stopped by the bot
 *
 * @property int|null $poll_answer_poll_id A user changed their answer in a non-anonymous poll. Bots receive
 * new votes only in polls that were sent by the bot itself.
 *
 * @property CallbackQuery $callbackQuery
 * @property Message $chat
 * @property Message $chat0
 * @property ChosenInlineResult $chosenInlineResult
 * @property EditedMessage $editedChannelPost
 * @property EditedMessage $editedMessage
 * @property InlineQuery $inlineQuery
 * @property Poll $poll
 * @property PreCheckoutQuery $preCheckoutQuery
 * @property ShippingQuery $shippingQuery
 */
class TelegramUpdate extends ActiveRecordEx
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram.telegram_update';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [
                [
                    'id',
                    'chat_id',
                    'message_id',
                    'edited_message_id',
                    'channel_post_id',
                    'edited_channel_post_id',
                    'inline_query_id',
                    'chosen_inline_result_id',
                    'callback_query_id',
                    'shipping_query_id',
                    'pre_checkout_query_id',
                    'poll_id',
                    'poll_answer_poll_id'
                ],
                'default',
                'value' => null
            ],
            [
                [
                    'id',
                    'chat_id',
                    'message_id',
                    'edited_message_id',
                    'channel_post_id',
                    'edited_channel_post_id',
                    'inline_query_id',
                    'chosen_inline_result_id',
                    'callback_query_id',
                    'shipping_query_id',
                    'pre_checkout_query_id',
                    'poll_id',
                    'poll_answer_poll_id'
                ],
                'integer'
            ],
            [['id'], 'unique'],
            [
                ['callback_query_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => CallbackQuery::class,
                'targetAttribute' => ['callback_query_id' => 'id']
            ],
            [
                ['chosen_inline_result_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ChosenInlineResult::class,
                'targetAttribute' => ['chosen_inline_result_id' => 'id']
            ],
            [
                ['edited_message_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => EditedMessage::class,
                'targetAttribute' => ['edited_message_id' => 'id']
            ],
            [
                ['edited_channel_post_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => EditedMessage::class,
                'targetAttribute' => ['edited_channel_post_id' => 'id']
            ],
            [
                ['inline_query_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => InlineQuery::class,
                'targetAttribute' => ['inline_query_id' => 'id']
            ],
            [
                ['chat_id', 'message_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Message::class,
                'targetAttribute' => ['chat_id' => 'chat_id', 'message_id' => 'id']
            ],
            [
                ['chat_id', 'channel_post_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Message::class,
                'targetAttribute' => ['chat_id' => 'chat_id', 'channel_post_id' => 'id']
            ],
            [
                ['poll_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Poll::class,
                'targetAttribute' => ['poll_id' => 'id']
            ],
            [
                ['pre_checkout_query_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PreCheckoutQuery::class,
                'targetAttribute' => ['pre_checkout_query_id' => 'id']
            ],
            [
                ['shipping_query_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ShippingQuery::class,
                'targetAttribute' => ['shipping_query_id' => 'id']
            ],
        ];
    }

    /**
     * Gets query for [[CallbackQuery]].
     *
     * @return ActiveQuery|CallbackQueryQuery
     */
    public function getCallbackQuery()
    {
        return $this->hasOne(CallbackQuery::class, ['id' => 'callback_query_id']);
    }

    /**
     * Gets query for [[Chat]].
     *
     * @return ActiveQuery|MessageQuery
     */
    public function getChat()
    {
        return $this->hasOne(Message::class, ['chat_id' => 'chat_id', 'id' => 'message_id']);
    }

    /**
     * Gets query for [[Chat0]].
     *
     * @return ActiveQuery|MessageQuery
     */
    public function getChat0()
    {
        return $this->hasOne(Message::class, ['chat_id' => 'chat_id', 'id' => 'channel_post_id']);
    }

    /**
     * Gets query for [[ChosenInlineResult]].
     *
     * @return ActiveQuery|ChosenInlineResultQuery
     */
    public function getChosenInlineResult()
    {
        return $this->hasOne(ChosenInlineResult::class, ['id' => 'chosen_inline_result_id']);
    }

    /**
     * Gets query for [[EditedChannelPost]].
     *
     * @return ActiveQuery|EditedMessageQuery
     */
    public function getEditedChannelPost()
    {
        return $this->hasOne(EditedMessage::class, ['id' => 'edited_channel_post_id']);
    }

    /**
     * Gets query for [[EditedMessage]].
     *
     * @return ActiveQuery|EditedMessageQuery
     */
    public function getEditedMessage()
    {
        return $this->hasOne(EditedMessage::class, ['id' => 'edited_message_id']);
    }

    /**
     * Gets query for [[InlineQuery]].
     *
     * @return ActiveQuery|InlineQueryQuery
     */
    public function getInlineQuery()
    {
        return $this->hasOne(InlineQuery::class, ['id' => 'inline_query_id']);
    }

    /**
     * Gets query for [[Poll]].
     *
     * @return ActiveQuery|PollQuery
     */
    public function getPoll()
    {
        return $this->hasOne(Poll::class, ['id' => 'poll_id']);
    }

    /**
     * Gets query for [[PreCheckoutQuery]].
     *
     * @return ActiveQuery|PreCheckoutQueryQuery
     */
    public function getPreCheckoutQuery()
    {
        return $this->hasOne(PreCheckoutQuery::class, ['id' => 'pre_checkout_query_id']);
    }

    /**
     * Gets query for [[ShippingQuery]].
     *
     * @return ActiveQuery|ShippingQueryQuery
     */
    public function getShippingQuery()
    {
        return $this->hasOne(ShippingQuery::class, ['id' => 'shipping_query_id']);
    }

    /**
     * {@inheritdoc}
     * @return TelegramUpdateQuery the active query used by this AR class.
     */
    public static function find($alias = null)
    {
        return new TelegramUpdateQuery(get_called_class(), ['as' => $alias]);
    }
}
