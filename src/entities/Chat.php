<?php
namespace onix\telegram\entities;

/**
 * Class Chat
 *
 * @link https://core.telegram.org/bots/api#chat
 *
 * @property-read int $id Unique identifier for this chat. This number may be greater than 32 bits and some programming
 * languages may have difficulty/silent defects in interpreting it. But it smaller than 52 bits, so a signed 64 bit
 * integer or double-precision float type are safe for storing this identifier.
 *
 * @property-read string $type Type of chat, can be either "private ", "group", "supergroup" or "channel"
 * @property-read string $title Optional. Title, for channels and group chats
 * @property-read string $username Optional. Username, for private chats, supergroups and channels if available
 * @property-read string $firstName Optional. First name of the other party in a private chat
 * @property-read string $lastName Optional. Last name of the other party in a private chat
 * @property-read ChatPhoto $photo Optional. Chat photo. Returned only in getChat.
 * @property-read string $description Optional. Description, for groups, supergroups and channel chats.
 * Returned only in getChat.
 *
 * @property-read string $inviteLink Optional. Chat invite link, for groups, supergroups and channel chats.
 * Each administrator in a chat generates their own invite links, so the bot must first generate the link
 * using exportChatInviteLink. Returned only in getChat.
 *
 * @property-read Message $pinnedMessage Optional. Pinned message, for groups, supergroups and channels.
 * Returned only in getChat.
 *
 * @property-read ChatPermissions $permissions Optional. Default chat member permissions, for groups and supergroups.
 * Returned only in getChat.
 *
 * @property-read int $slowModeDelay Optional. For supergroups, the minimum allowed delay between consecutive messages
 * sent by each unpriviledged user. Returned only in getChat.
 *
 * @property-read string $stickerSetName Optional. For supergroups, name of group sticker set. Returned only in getChat.
 * @property-read bool $canSetStickerSet Optional. True, if the bot can change the group sticker set.
 * Returned only in getChat.
 *
 * @property bool allMembersAreAdministrators Optional. True if a group has 'All Members Are Admins' enabled.
 */
class Chat extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return [
            'id',
            'type',
            'title',
            'username',
            'firstName',
            'lastName',
            'photo',
            'description',
            'inviteLink',
            'pinnedMessage',
            'permissions',
            'slowModeDelay',
            'stickerSetName',
            'canSetStickerSet',
            'allMembersAreAdministrators'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'photo' => ChatPhoto::class,
            'pinnedMessage' => Message::class,
            'permissions' => ChatPermissions::class,
        ];
    }

    public function __construct($config)
    {
        parent::__construct($config);

        $id = $this->id;
        $type = $this->type;
        if (!$type) {
            $id > 0 && $this->type = 'private';
            $id < 0 && $this->type = 'group';
        }
    }

    /**
     * Try to mention the user of this chat, else return the title
     *
     * @param bool $escape_markdown
     *
     * @return string|null
     */
    public function tryMention(bool $escape_markdown = false)
    {
        if ($this->isPrivateChat()) {
            return parent::tryMention($escape_markdown);
        }

        return $this->title;
    }

    /**
     * Check if this is a group chat
     *
     * @return bool
     */
    public function isGroupChat()
    {
        return $this->type === 'group' || $this->id < 0;
    }

    /**
     * Check if this is a private chat
     *
     * @return bool
     */
    public function isPrivateChat()
    {
        return $this->type === 'private';
    }

    /**
     * Check if this is a super group
     *
     * @return bool
     */
    public function isSuperGroup()
    {
        return $this->type === 'supergroup';
    }

    /**
     * Check if this is a channel
     *
     * @return bool
     */
    public function isChannel()
    {
        return $this->type === 'channel';
    }
}
