<?php
namespace onix\telegram\migrations\models_v1;

use onix\data\ActiveRecordEx;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "telegram.inline_query".
 *
 * @property int $id Unique identifier for this query
 * @property int $user_id Unique user identifier
 * @property string|null $location Location of the user
 * @property string $query Text of the query
 * @property string|null $offset Offset of the result
 * @property string $created_at Entry date creation
 *
 * @property TelegramUpdate[] $telegramUpdates
 * @property User $user
 */
class InlineQuery extends ActiveRecordEx
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram.inline_query';
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
            [['id', 'user_id', 'query'], 'required'],
            [['id', 'user_id'], 'default', 'value' => null],
            [['id', 'user_id'], 'number'],
            [['query'], 'string'],
            [['created_at'], 'safe'],
            [['location', 'offset'], 'string', 'max' => 255],
            [['id'], 'unique'],
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
        return $this->hasMany(TelegramUpdate::class, ['inline_query_id' => 'id']);
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
     * @return InlineQueryQuery the active query used by this AR class.
     */
    public static function find($alias = null)
    {
        return new InlineQueryQuery(get_called_class(), ['as' => $alias]);
    }
}
