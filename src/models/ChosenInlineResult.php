<?php
namespace onix\telegram\models;

use onix\data\ActiveRecordEx;
use onix\telegram\entities\ChosenInlineResult as ChosenInlineResultEntity;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "telegram.chosen_inline_result".
 *
 * @property string $_id Unique identifier for this entry
 * @property int|null $userId The user that chose the result
 * @property string|null $location Sender location, only for bots that require user location
 * @property string|null $inlineMessageId Identifier of the sent inline message
 * @property string $query The query that was used to obtain the result
 */
class ChosenInlineResult extends TelegramActiveRecord
{
    protected ?string $entityClass = ChosenInlineResultEntity::class;

    protected array $attributeMap = [
        'resultId' => '_id',
        'from' => 'userId'
    ];

    /**
     * {@inheritdoc}
     */
    public static function collectionName(): array|string
    {
        return 'telegram_chosen_inline_result';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['query'], 'required'],
            [['userId'], 'integer'],
            [['query'], 'string'],
            [['_id', 'location', 'inlineMessageId'], 'string'],
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
     * @return ChosenInlineResultQuery the active query used by this AR class.
     */
    public static function find(): ChosenInlineResultQuery
    {
        return new ChosenInlineResultQuery(get_called_class());
    }
}
