<?php
namespace onix\telegram\models;

use onix\data\ActiveRecordEx;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "telegram.chosen_inline_result".
 *
 * @property int $id Unique identifier for this entry
 * @property string $result_id The unique identifier for the result that was chosen
 * @property int|null $user_id The user that chose the result
 * @property string|null $location Sender location, only for bots that require user location
 * @property string|null $inline_message_id Identifier of the sent inline message
 * @property string $query The query that was used to obtain the result
 * @property string $created_at Entry date creation
 *
 * @property TelegramUpdate[] $telegramUpdates
 * @property User $user
 */
class ChosenInlineResult extends ActiveRecordEx
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram.chosen_inline_result';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $now = (self::getDb()->driverName === 'pgsql') ? "timezone('GMT'::text, now())" : 'now()';

        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => [],
                ],
                'value' => new Expression($now),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['result_id', 'query'], 'required'],
            [['user_id'], 'default', 'value' => null],
            [['user_id'], 'integer'],
            [['query'], 'string'],
            [['created_at'], 'safe'],
            [['result_id', 'location', 'inline_message_id'], 'string', 'max' => 255],
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
     * Gets query for [[TelegramUpdates]].
     *
     * @return ActiveQuery|TelegramUpdateQuery
     */
    public function getTelegramUpdates()
    {
        return $this->hasMany(TelegramUpdate::class, ['chosen_inline_result_id' => 'id']);
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
     * @return ChosenInlineResultQuery the active query used by this AR class.
     */
    public static function find($alias = null)
    {
        return new ChosenInlineResultQuery(get_called_class(), ['as' => $alias]);
    }
}
