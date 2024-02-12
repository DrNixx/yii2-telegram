<?php
namespace onix\telegram\models;

use MongoDB\BSON\UTCDateTime;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for table "telegram.conversation".
 *
 * @property object $_id Unique identifier for this entry
 * @property int|null $userId Unique user identifier
 * @property int|null $chatId Unique chat identifier
 * @property string $status Identifier of the message sent via the bot in inline mode, that originated the query
 * @property string $command Default command to execute
 * @property string|null $notes Data stored from command
 * @property string $createdAt Entry date creation
 * @property string $updatedAt Entry date update
 */
class Conversation extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName(): array|string
    {
        return 'telegram_conversation';
    }

    public function attributes(): array
    {
        return [
            '_id',
            'userId',
            'chatId',
            'status',
            'command',
            'notes',
            'createdAt',
            'updatedAt',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['createdAt', 'updatedAt'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updatedAt'],
                ],
                'value' => new UTCDateTime(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['userId', 'chatId'], 'number'],
            [['notes'], 'string'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['status'], 'string', 'max' => 15],
            [['command'], 'string', 'max' => 160],
            [
                ['chatId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Chat::class,
                'targetAttribute' => ['chatId' => '_id']
            ],
            [
                ['userId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['userId' => '_id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     * @return ConversationQuery the active query used by this AR class.
     */
    public static function find($alias = null): ConversationQuery
    {
        return new ConversationQuery(get_called_class());
    }
}
