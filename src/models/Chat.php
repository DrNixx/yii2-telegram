<?php
namespace onix\telegram\models;

use onix\telegram\entities\Chat as ChatEntity;

/**
 * This is the model class for "telegram chat".
 *
 * @property int $_id Unique identifier for this chat
 * @property string $type Type of chat, can be either private, group, supergroup or channel
 * @property string $title Title, for supergroups, channels and group chats
 * @property string|null $username Username, for private chats, supergroups and channels if available
 * @property string|null $firstName First name of the other party in a private chat
 * @property string|null $lastName Last name of the other party in a private chat
 * @property bool $allMembersAreAdministrators True if all members of this group are admins
 * @property int|null $oldId Unique chat identifier, this is filled when a group is converted to a supergroup
 */
class Chat extends TelegramActiveRecord
{
    protected ?string $entityClass = ChatEntity::class;

    protected array $ownAttributes = ['oldId'];

    /**
     * {@inheritdoc}
     */
    public static function collectionName(): array|string
    {
        return 'telegram_chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['type'], 'required'],
            [['oldId'], 'integer'],
            [['allMembersAreAdministrators'], 'boolean'],
            [['type'], 'string', 'max' => 15],
            [['title', 'firstName', 'lastName', 'username'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     * @return ChatQuery the active query used by this AR class.
     */
    public static function find($alias = null): ChatQuery
    {
        return new ChatQuery(get_called_class());
    }
}
