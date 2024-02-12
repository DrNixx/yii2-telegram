<?php
namespace onix\telegram\models;

use MongoDB\BSON\UTCDateTime;
use onix\telegram\entities\MessageEntity;
use onix\telegram\entities\Poll as PollEntity;
use onix\telegram\entities\PollOption;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "telegram.poll".
 *
 * @property string $_id Unique poll identifier
 * @property string $question Poll question
 * @property PollOption[] $options List of poll options
 * @property int|null $totalVoterCount Total number of users that voted in the poll
 * @property bool $isClosed True, if the poll is closed
 * @property bool $isAnonymous True, if the poll is anonymous
 * @property string|null $type Poll type, currently can be "regular" or "quiz"
 * @property bool $allowsMultipleAnswers True, if the poll allows multiple answers
 * @property int|null $correctOptionId 0-based identifier of the correct answer option. Available only for polls
 * in the quiz mode, which are closed, or was sent (not forwarded) by the bot or to the private chat with the bot.
 *
 * @property string|null $explanation Text that is shown when a user chooses an incorrect answer or taps on
 * the lamp icon in a quiz-style poll, 0-200 characters
 *
 * @property MessageEntity[] $explanationEntities Special entities like usernames, URLs, bot commands, etc.
 * that appear in the explanation
 *
 * @property int|null $openPeriod Amount of time in seconds the poll will be active after creation
 * @property UTCDateTime|null $closeDate Point in time when the poll will be automatically closed
 */
class Poll extends TelegramActiveRecord
{
    protected ?string $entityClass = PollEntity::class;

    /**
     * {@inheritdoc}
     */
    public static function collectionName(): array|string
    {
        return 'telegram_poll';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['_id', 'question', 'options'], 'required'],
            [['_id'], 'string'],
            [['totalVoterCount', 'correctOptionId', 'openPeriod'], 'integer'],
            [['isClosed', 'isAnonymous', 'allowsMultipleAnswers'], 'boolean'],
            [['closeDate', 'options', 'explanationEntities'], 'safe'],
            [['question', 'type', 'explanation'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     * @return PollQuery the active query used by this AR class.
     */
    public static function find(): PollQuery
    {
        return new PollQuery(get_called_class());
    }
}
