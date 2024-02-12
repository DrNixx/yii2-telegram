<?php
namespace onix\telegram\models;

use onix\telegram\entities\PollAnswer as PollAnswerEntity;

/**
 * This is the model class for table "telegram.poll_answer".
 *
 * @property object $_id
 * @property string $pollId Unique poll identifier
 * @property int $userId Unique user identifier
 * @property array|null $optionIds 0-based identifiers of answer options, chosen by the user. May be empty
 * if the user retracted their vote.
 */
class PollAnswer extends TelegramActiveRecord
{
    protected ?string $entityClass = PollAnswerEntity::class;

    protected array $attributeMap = [
        'user' => 'userId'
    ];

    /**
     * {@inheritdoc}
     */
    public static function collectionName(): array|string
    {
        return 'telegram_poll_answer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['pollId', 'userId'], 'required'],
            [['pollId'], 'string'],
            [['userId'], 'integer'],
            [['optionIds'], 'safe'],
            [
                ['pollId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Poll::class,
                'targetAttribute' => ['pollId' => '_id']
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
     * @return PollAnswerQuery the active query used by this AR class.
     */
    public static function find(): PollAnswerQuery
    {
        return new PollAnswerQuery(get_called_class());
    }
}
