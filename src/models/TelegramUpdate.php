<?php
namespace onix\telegram\models;

use onix\telegram\entities\Update;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "telegram.telegram_update".
 *
 * @property int $_id Update's unique identifier
 * @property int|null $chatId Unique chat identifier
 * @property int|null $messageId New incoming message of any kind - text, photo, sticker, etc.
 * @property object|null $editedMessageId New version of a message that is known to the bot and was edited
 * @property int|null $channelPostId New incoming channel post of any kind - text, photo, sticker, etc.
 * @property int|null $editedChannelPostId New version of a channel post that is known to the bot and was edited
 * @property int|null $inlineQueryId New incoming inline query
 * @property string|null $chosenInlineResultId The result of an inline query that was chosen by a user and sent
 * to their chat partner
 *
 * @property string|null $callbackQueryId New incoming callback query
 * @property int|null $shippingQueryId New incoming shipping query. Only for invoices with flexible price
 * @property int|null $preCheckoutQueryId New incoming pre-checkout query. Contains full information about checkout
 * @property int|null $pollId New poll state. Bots receive only updates about polls,
 * which are sent or stopped by the bot
 *
 * @property int|null $pollAnswerId A user changed their answer in a non-anonymous poll. Bots receive
 * new votes only in polls that were sent by the bot itself.
 *
 * @property object|null $myChatMemberId The bot's chat member status was updated in a chat. For private chats,
 * this update is received only when the bot is blocked or unblocked by the user.
 *
 * @property object|null $chatMemberId A chat member's status was updated in a chat. The bot must be an
 * administrator in the chat and must explicitly specify “chat_member” in the list of allowed_updates to receive these updates.
 *
 * @property object|null $chatJoinRequestId A chat member's status was updated in a chat. The bot must be an
 *  administrator in the chat and must explicitly specify “chat_member” in the list of allowed_updates to receive these updates.
 *
 * @property-read int $id Update's unique identifier
 */
class TelegramUpdate extends TelegramActiveRecord
{
    protected ?string $entityClass = Update::class;

    protected array $attributeMap = [
        'updateId' => '_id',
        'message' => ['chatId', 'messageId'],
        'editedMessage' => 'editedMessageId',
        'channelPost' => 'channelPostId',
        'editedChannelPost' => 'editedChannelPostId',
        'inlineQuery' => 'inlineQueryId',
        'chosenInlineResult' => 'chosenInlineResultId',
        'callbackQuery' => 'callbackQueryId',
        'shippingQuery' => 'shippingQueryId',
        'preCheckoutQuery' => 'preCheckoutQueryId',
        'poll' => 'pollId',
        'pollAnswer' => 'pollAnswerId',
        'myChatMember' => 'myChatMemberId',
        'chatMember' => 'chatMemberId',
        'chatJoinRequest' => 'chatJoinRequestId',
    ];

    /**
     * {@inheritdoc}
     */
    public static function collectionName(): array|string
    {
        return 'telegram_update';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return ArrayHelper::merge(parent::rules(), [
            [
                [
                    'chatId',
                    'messageId',
                    'channelPostId',
                    'inlineQueryId',
                ], 'integer'
            ],
            [
                [
                    'callbackQueryId',
                    'chosenInlineResultId',
                    'pollId',
                    'preCheckoutQueryId',
                    'shippingQueryId',
                ], 'string'
            ],
            [
                [
                    'editedMessageId',
                    'editedChannelPostId',
                    'pollAnswerId',
                    'myChatMemberId',
                    'chatMemberId'
                ], 'safe'
            ],
        ]);
    }

    public function getId(): int
    {
        return $this->_id;
    }

    /**
     * {@inheritdoc}
     * @return TelegramUpdateQuery the active query used by this AR class.
     */
    public static function find(): TelegramUpdateQuery
    {
        return new TelegramUpdateQuery(get_called_class());
    }
}
