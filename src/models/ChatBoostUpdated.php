<?php

namespace onix\telegram\models;

use onix\telegram\entities\chatBoost\ChatBoost;
use onix\telegram\entities\chatBoost\ChatBoostUpdated as ChatBoostUpdatedEntity;

/**
 * @property object $_id
 * @property int $chatId Chat which was boosted
 * @property ChatBoost $boost Information about the chat boost
 */
class ChatBoostUpdated extends TelegramActiveRecord
{
    protected ?string $entityClass = ChatBoostUpdatedEntity::class;

    protected array $ownAttributes = ['_id'];

    protected array $attributeMap = [
        'chat' => 'chatId',
    ];

    /**
     * @inheritdoc
     */
    public static function collectionName(): array|string
    {
        return 'telegram_chat_boost_updated';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['chatId'], 'integer'],
            [['boost'], 'safe'],
        ];
    }
}