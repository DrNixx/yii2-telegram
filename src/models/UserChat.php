<?php
namespace onix\telegram\models;

use onix\data\ActiveRecordEx;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "telegram.user_chat".
 *
 * @property int $user_id Unique user identifier
 * @property int $chat_id Unique user or chat identifier
 *
 * @property Chat $chat
 * @property User $user
 */
class UserChat extends ActiveRecordEx
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram.user_chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'chat_id'], 'required'],
            [['user_id', 'chat_id'], 'default', 'value' => null],
            [['user_id', 'chat_id'], 'integer'],
            [['user_id', 'chat_id'], 'unique', 'targetAttribute' => ['user_id', 'chat_id']],
            [
                ['chat_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Chat::class,
                'targetAttribute' => ['chat_id' => 'id']
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
     * @return UserChatQuery the active query used by this AR class.
     */
    public static function find($alias = null)
    {
        return new UserChatQuery(get_called_class(), ['as' => $alias]);
    }
}
