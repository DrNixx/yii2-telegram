<?php
namespace onix\telegram\models;

use MongoDB\BSON\UTCDateTime;
use onix\telegram\entities\CallbackQuery as CallbackQueryEntity;

/**
 * This is the model class for table "telegram.callback_query".
 *
 * @property string $_id Unique identifier for this query
 * @property int|null $userId Unique user identifier
 * @property int|null $chatId Unique chat identifier
 * @property int|null $messageId Unique message identifier
 * @property string|null $inlineMessageId Identifier of the message sent via the bot in inline mode,
 * that originated the query
 *
 * @property string|null $chatInstance Global identifier, uniquely corresponding to the chat to which the message
 * with the callback button was sent
 *
 * @property string|null $data Data associated with the callback button
 * @property string|null $gameShortName Short name of a Game to be returned, serves as the unique identifier
 * for the game
 *
 * @property UTCDateTime $createdAt Entry date creation
 */
class CallbackQuery extends TelegramActiveRecord
{
    protected ?string $entityClass = CallbackQueryEntity::class;

    protected array $attributeMap = [
        'id' => '_id',
        'from' => 'userId',
        'message' => ['chatId', 'messageId'],
    ];

    /**
     * {@inheritdoc}
     */
    public static function collectionName(): array|string
    {
        return 'telegram_callback_query';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['userId', 'chatId', 'messageId'], 'integer'],
            [['_id', 'inlineMessageId', 'chatInstance', 'data', 'gameShortName'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     * @return CallbackQueryQuery the active query used by this AR class.
     */
    public static function find($alias = null): CallbackQueryQuery
    {
        return new CallbackQueryQuery(get_called_class());
    }
}
