<?php

namespace onix\telegram\models;

use MongoDB\BSON\UTCDateTime;
use onix\telegram\entities\chatBoost\ChatBoostRemoved as ChatBoostRemovedEntity;
use onix\telegram\entities\chatBoost\ChatBoostSource;

/**
 * @property object $_id
 * @property int $chatId Chat which was boosted
 * @property string $boostId Unique identifier of the boost
 * @property UTCDateTime $removeDate Point in time (Unix timestamp) when the boost was removed
 * @property ChatBoostSource $source Source of the removed boost
 */
class ChatBoostRemoved extends TelegramActiveRecord
{
    protected ?string $entityClass = ChatBoostRemovedEntity::class;

    protected array $ownAttributes = ['_id'];

    protected array $attributeMap = [
        'chat' => 'chatId',
    ];

    /**
     * @inheritdoc
     */
    public static function collectionName(): array|string
    {
        return 'telegram_chat_boost_removed';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['chatId'], 'integer'],
            [['boostId'], 'string'],
            [['removeDate'], 'safe'],
            [['source'], 'safe'],
        ];
    }
}