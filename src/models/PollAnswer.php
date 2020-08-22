<?php
namespace onix\telegram\models;

use onix\data\ActiveRecordEx;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "telegram.poll_answer".
 *
 * @property int $poll_id Unique poll identifier
 * @property int $user_id Unique user identifier
 * @property string|null $option_ids 0-based identifiers of answer options, chosen by the user. May be empty
 * if the user retracted their vote.
 *
 * @property string $created_at Entry date creation
 *
 * @property Poll $poll
 * @property User $user
 */
class PollAnswer extends ActiveRecordEx
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram.poll_answer';
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
            [['poll_id', 'user_id'], 'required'],
            [['poll_id', 'user_id'], 'default', 'value' => null],
            [['poll_id', 'user_id'], 'integer'],
            [['option_ids'], 'string'],
            [['created_at'], 'safe'],
            [['poll_id', 'user_id'], 'unique', 'targetAttribute' => ['poll_id', 'user_id']],
            [
                ['poll_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Poll::class,
                'targetAttribute' => ['poll_id' => 'id']
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
     * Gets query for [[Poll]].
     *
     * @return ActiveQuery|PollQuery
     */
    public function getPoll()
    {
        return $this->hasOne(Poll::class, ['id' => 'poll_id']);
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
     * @return PollAnswerQuery the active query used by this AR class.
     */
    public static function find($alias = null)
    {
        return new PollAnswerQuery(get_called_class(), ['as' => $alias]);
    }
}
