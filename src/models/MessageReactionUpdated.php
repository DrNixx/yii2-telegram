<?php

namespace onix\telegram\models;

use MongoDB\BSON\UTCDateTime;
use onix\telegram\entities\chatBoost\ChatBoostUpdated as ChatBoostUpdatedEntity;
use onix\telegram\entities\reaction\MessageReactionUpdated as MessageReactionUpdatedEntity;
use onix\telegram\entities\reaction\ReactionType;

/**
 * @property object $_id
 * @property int $chatId Chat which was boosted
 * @property int $messageId Unique identifier of the message inside the chat
 * @property int $userId Optional. The user that changed the reaction, if the user isn't anonymous
 * @property int $actorChatId Optional. The chat on behalf of which the reaction was changed, if the user is anonymous
 * @property UTCDateTime $date Date of the change in Unix time
 * @property ReactionType[] $oldReaction Previous list of reaction types that were set by the user
 * @property ReactionType[] $newReaction New list of reaction types that have been set by the user
 */
class MessageReactionUpdated extends TelegramActiveRecord
{
    protected ?string $entityClass = MessageReactionUpdatedEntity::class;

    protected array $ownAttributes = ['_id'];

    protected array $attributeMap = [
        'chat' => 'chatId',
        'user' => 'userId',
        'actorChat' => 'actorChatId',
    ];

    /**
     * @inheritdoc
     */
    public static function collectionName(): array|string
    {
        return 'telegram_message_reaction_updated';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['chatId', 'messageId', 'userId', 'actorChatId'], 'integer'],
            [['oldReaction', 'newReaction'], 'safe'],
            [['date'], 'safe'],
        ];
    }
}
