<?php
namespace onix\telegram\models;

use MongoDB\BSON\UTCDateTime;
use onix\telegram\entities\ChatMemberUpdated as ChatMemberUpdatedEntity;

/**
 * This is the model class for table "telegram.chatmember_update".
 *
 * @property object $_id Unique identifier
 * @property int $chatId Chat the user belongs to
 * @property int $userId Performer of the action, which resulted in the change
 * @property UTCDateTime $date Date the change was done in Unix time
 * @property string $oldChatMember Previous information about the chat member
 * @property string $newChatMember New information about the chat member
 * @property string $inviteLink Optional. Chat invite link, which was used by the user to join the chat; for joining by invite link events only.
 */
class ChatMemberUpdated extends TelegramActiveRecord
{
    protected ?string $entityClass = ChatMemberUpdatedEntity::class;

    protected array $ownAttributes = ['_id'];

    protected array $attributeMap = [
        'chat' => 'chatId',
        'from' => 'userId',
    ];

    /**
     * @inheritdoc
     */
    public static function collectionName(): array|string
    {
        return 'telegram_chat_member_updated';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['chatId', 'userId'], 'integer'],
            [['date'], 'safe'],
            [['oldChatMember', 'newChatMember', 'inviteLink'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     * @return ChatMemberUpdatedQuery the active query used by this AR class.
     */
    public static function find(): ChatMemberUpdatedQuery
    {
        return new ChatMemberUpdatedQuery(get_called_class());
    }
}