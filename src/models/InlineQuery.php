<?php
namespace onix\telegram\models;

use onix\telegram\entities\InlineQuery as InlineQueryEntity;

/**
 * This is the model class for table "telegram.inline_query".
 *
 * @property string $_id Unique identifier for this query
 * @property int $userId Unique user identifier
 * @property string|null $location Location of the user
 * @property string $query Text of the query
 * @property string|null $offset Offset of the result
 *
 * @property TelegramUpdate[] $telegramUpdates
 * @property User $user
 */
class InlineQuery extends TelegramActiveRecord
{
    protected ?string $entityClass = InlineQueryEntity::class;

    protected array $attributeMap = [
        'id' => '_id',
        'from' => 'userId'
    ];

    /**
     * {@inheritdoc}
     */
    public static function collectionName(): array|string
    {
        return 'telegram_inline_query';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['userId', 'query'], 'required'],
            [['userId'], 'integer'],
            [['_id', 'query'], 'string'],
            [['location', 'offset'], 'string', 'max' => 255],
            [['location'], 'safe'],
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
     * @return InlineQueryQuery the active query used by this AR class.
     */
    public static function find(): InlineQueryQuery
    {
        return new InlineQueryQuery(get_called_class());
    }
}
