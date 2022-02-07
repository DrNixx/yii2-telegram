<?php

namespace onix\telegram\models;

use onix\data\ActiveRecordEx;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "telegram.edited_message".
 *
 * @property int $id Unique identifier for this entry
 * @property int|null $chat_id Unique chat identifier
 * @property int|null $message_id Unique message identifier
 * @property int|null $user_id Unique user identifier
 * @property string|null $edit_date Date the message was last edited
 * @property string|null $text For text messages, the actual UTF-8 text of the message max message length 4096 char utf8
 * @property string|null $entities For text messages, special entities like usernames, URLs, bot commands, etc.
 * that appear in the text
 *
 * @property string|null $caption For message with caption, the actual UTF-8 text of the caption
 *
 * @property Chat $chat
 * @property Message $chat0
 * @property TelegramUpdate[] $telegramUpdates
 * @property TelegramUpdate[] $telegramUpdates0
 * @property User $user
 */
class EditedMessage extends ActiveRecordEx
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram.edited_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id', 'message_id', 'user_id'], 'default', 'value' => null],
            [['chat_id', 'message_id', 'user_id'], 'number'],
            [['edit_date'], 'safe'],
            [['text', 'entities', 'caption'], 'string'],
            [
                ['chat_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Chat::class,
                'targetAttribute' => ['chat_id' => 'id']
            ],
            [
                ['chat_id', 'message_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Message::class,
                'targetAttribute' => ['chat_id' => 'chat_id', 'message_id' => 'id']
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
        ];
    }

    /**
     * Gets query for [[Chat]].
     *
     * @return ActiveQuery|ChatQuery
     */
    public function getChat()
    {
        return $this->hasOne(Chat::class, ['id' => 'chat_id']);
    }

    /**
     * Gets query for [[Chat0]].
     *
     * @return ActiveQuery|MessageQuery
     */
    public function getChat0()
    {
        return $this->hasOne(Message::class, ['chat_id' => 'chat_id', 'id' => 'message_id']);
    }

    /**
     * Gets query for [[TelegramUpdates]].
     *
     * @return ActiveQuery|TelegramUpdateQuery
     */
    public function getTelegramUpdates()
    {
        return $this->hasMany(TelegramUpdate::class, ['edited_message_id' => 'id']);
    }

    /**
     * Gets query for [[TelegramUpdates0]].
     *
     * @return ActiveQuery|TelegramUpdateQuery
     */
    public function getTelegramUpdates0()
    {
        return $this->hasMany(TelegramUpdate::class, ['edited_channel_post_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return EditedMessageQuery the active query used by this AR class.
     */
    public static function find($alias = null)
    {
        return new EditedMessageQuery(get_called_class(), ['as' => $alias]);
    }
}
