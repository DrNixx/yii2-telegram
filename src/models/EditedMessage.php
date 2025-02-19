<?php

namespace onix\telegram\models;

use MongoDB\BSON\UTCDateTime;
use onix\telegram\entities\EditedMessage as EditedMessageEntity;

/**
 * This is the model class for table "telegram.edited_message".
 *
 * @property object $_id Unique identifier for this entry
 * @property int|null $chatId Unique chat identifier
 * @property int|null $messageId Unique message identifier
 * @property int|null $userId Unique user identifier
 * @property UTCDateTime|null $date Date the message was last edited
 * @property UTCDateTime|null $editDate Date the message was last edited
 * @property string|null $text For text messages, the actual UTF-8 text of the message max message length 4096 char utf8
 * @property mixed|null $entities For text messages, special entities like usernames, URLs, bot commands, etc.
 * that appear in the text
 *
 * @property string|null $caption For message with caption, the actual UTF-8 text of the caption
 */
class EditedMessage extends TelegramActiveRecord
{
    protected ?string $entityClass = EditedMessageEntity::class;

    protected array $ownAttributes = ['_id', 'editDate'];

    protected array $attributeMap = [
        'chat' => 'chatId',
        'from' => 'userId',
    ];

    /**
     * {@inheritdoc}
     */
    public static function collectionName(): array|string
    {
        return 'telegram_edited_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['chatId', 'messageId', 'userId'], 'integer'],
            [['date', 'editDate'], 'safe'],
            [['text', 'entities', 'caption'], 'string'],
            [['entities'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     * @return EditedMessageQuery the active query used by this AR class.
     */
    public static function find(): EditedMessageQuery
    {
        return new EditedMessageQuery(get_called_class());
    }
}
