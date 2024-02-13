<?php

namespace onix\telegram\models;

use onix\telegram\entities\ChatInviteLink;
use onix\telegram\entities\ChatJoinRequest as ChatJoinRequestEntity;

/**
 * Represents Repo for onix\telegram\entities\ChatJoinRequest
 *
 * @property object $_id
 * @property int $chatId Chat the user belongs to
 * @property int $userId Performer of the action, which resulted in the change
 *
 * @property int $userChatId Identifier of a private chat with the user who sent the join request.
 * This number may have more than 32 significant bits and some programming languages may have difficulty/silent
 * defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision
 * float type are safe for storing this identifier. The bot can use this identifier for 5 minutes to send messages
 * until the join request is processed, assuming no other administrator contacted the user.
 *
 * @property int $date Date the request was sent in Unix time
 * @property string $bio Optional. Bio of the user.
 * @property ChatInviteLink $inviteLink Optional. Chat invite link that was used by the user to send the join request
 */
class ChatJoinRequest extends TelegramActiveRecord
{
    protected ?string $entityClass = ChatJoinRequestEntity::class;

    //    protected array $ownAttributes = ['_id'];

    protected array $attributeMap = [
        'from' => 'userId',
        'chat' => 'chatId',
    ];

    /**
     * @inheritdoc
     */
    public static function collectionName(): array|string
    {
        return 'telegram_chat_join_request';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['chatId', 'userId', 'userChatId'], 'integer'],
            [['date'], 'safe'],
            [['bio'], 'string'],
            [['inviteLink'], 'safe'],
        ];
    }

}