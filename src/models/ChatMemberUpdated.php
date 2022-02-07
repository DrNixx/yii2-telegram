<?php
namespace onix\telegram\models;

use onix\data\ActiveRecordEx;

/**
 * This is the model class for table "telegram.chatmember_update".
 *
 * @property int $id Unique identifier
 * @property int $chat_id Chat the user belongs to
 * @property int $user_id Performer of the action, which resulted in the change
 * @property string $date Date the change was done in Unix time
 * @property string $old_chat_member Previous information about the chat member
 * @property string $new_chat_member New information about the chat member
 * @property string $invite_link Optional. Chat invite link, which was used by the user to join the chat; for joining by invite link events only.
 * @property string $created_at Date the change was done in Unix time
 */
class ChatMemberUpdated extends ActiveRecordEx
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'telegram.chat_member_updated';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'chat_id',
                    'user_id',
                    'date',
                    'old_chat_member',
                    'new_chat_member',
                    'invite_link'
                ],
                'default',
                'value' => null
            ],
            [
                [
                    'chat_id',
                    'user_id',
                ],
                'number'
            ],
            [['date', 'created_at'], 'safe'],
            [['old_chat_member', 'new_chat_member', 'invite_link'], 'string'],
            [
                ['chat_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Chat::class,
                'targetAttribute' => ['chat_id' => 'id'],
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id'],
            ],
        ];
    }

    /**
     * Gets query for [[Chat]].
     *
     * @return ChatQuery
     *
     * @noinspection PhpReturnDocTypeMismatchInspection
     */
    public function getChat()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->hasOne(Chat::class, ['chat_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return UserQuery
     *
     * @noinspection PhpReturnDocTypeMismatchInspection
     */
    public function getUser()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->hasOne(User::class, ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ChatMemberUpdatedQuery the active query used by this AR class.
     */
    public static function find($alias = null)
    {
        return new ChatMemberUpdatedQuery(get_called_class(), ['as' => $alias]);
    }
}