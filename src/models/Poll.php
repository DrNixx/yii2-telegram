<?php
namespace onix\telegram\models;

use onix\data\ActiveRecordEx;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "telegram.poll".
 *
 * @property int $id Unique poll identifier
 * @property string $question Poll question
 * @property string $options List of poll options
 * @property int|null $total_voter_count Total number of users that voted in the poll
 * @property bool $is_closed True, if the poll is closed
 * @property bool $is_anonymous True, if the poll is anonymous
 * @property string|null $type Poll type, currently can be "regular" or "quiz"
 * @property bool $allows_multiple_answers True, if the poll allows multiple answers
 * @property int|null $correct_option_id 0-based identifier of the correct answer option. Available only for polls
 * in the quiz mode, which are closed, or was sent (not forwarded) by the bot or to the private chat with the bot.
 *
 * @property string|null $explanation Text that is shown when a user chooses an incorrect answer or taps on
 * the lamp icon in a quiz-style poll, 0-200 characters
 *
 * @property string $explanation_entities Special entities like usernames, URLs, bot commands, etc.
 * that appear in the explanation
 *
 * @property int|null $open_period Amount of time in seconds the poll will be active after creation
 * @property string|null $close_date Point in time when the poll will be automatically closed
 * @property string $created_at Entry date creation
 *
 * @property PollAnswer[] $pollAnswers
 * @property TelegramUpdate[] $telegramUpdates
 * @property User[] $users
 */
class Poll extends ActiveRecordEx
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram.poll';
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
            [['id', 'question', 'options', 'explanation_entities'], 'required'],
            [['id', 'total_voter_count', 'correct_option_id', 'open_period'], 'default', 'value' => null],
            [['id', 'total_voter_count', 'correct_option_id', 'open_period'], 'number'],
            [['options', 'explanation_entities'], 'string'],
            [['is_closed', 'is_anonymous', 'allows_multiple_answers'], 'boolean'],
            [['close_date', 'created_at'], 'safe'],
            [['question', 'type', 'explanation'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * Gets query for [[PollAnswers]].
     *
     * @return ActiveQuery|PollAnswerQuery
     */
    public function getPollAnswers()
    {
        return $this->hasMany(PollAnswer::class, ['poll_id' => 'id']);
    }

    /**
     * Gets query for [[TelegramUpdates]].
     *
     * @return ActiveQuery|TelegramUpdateQuery
     */
    public function getTelegramUpdates()
    {
        return $this->hasMany(TelegramUpdate::class, ['poll_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->via('pollAnswers');
    }

    /**
     * {@inheritdoc}
     * @return PollQuery the active query used by this AR class.
     */
    public static function find($alias = null)
    {
        return new PollQuery(get_called_class(), ['as' => $alias]);
    }
}
