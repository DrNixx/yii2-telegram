<?php

namespace onix\telegram\models;

use MongoDB\BSON\UTCDateTime;
use onix\telegram\entities\reaction\MessageReactionCountUpdated as MessageReactionCountUpdatedEntity;
use onix\telegram\entities\reaction\ReactionCount;

/**
 * @property object $_id
 * @property int $chatId Chat which was boosted
 * @property int $messageId Unique identifier of the message inside the chat
 * @property UTCDateTime $date Date of the change in Unix time
 * @property ReactionCount[] $reactions List of reactions that are present on the message
 */
class MessageReactionCountUpdated extends TelegramActiveRecord
{
    protected ?string $entityClass = MessageReactionCountUpdatedEntity::class;

    protected array $ownAttributes = ['_id'];

    protected array $attributeMap = [
        'chat' => 'chatId',
    ];

    /**
     * @inheritdoc
     */
    public static function collectionName(): array|string
    {
        return 'telegram_message_reaction_count_updated';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['chatId', 'messageId'], 'integer'],
            [['reactions'], 'safe'],
            [['date'], 'safe'],
        ];
    }
}
