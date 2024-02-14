<?php
namespace onix\telegram\models;

use MongoDB\BSON\UTCDateTime;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for table "telegram.request_limiter".
 *
 * @property object $_id Unique identifier for this entry
 * @property int|null $chatId Unique chat identifier
 * @property string|null $inlineMessageId Identifier of the sent inline message
 * @property string|null $method Request method
 * @property UTCDateTime $date
 */
class RequestLimiter extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName(): array|string
    {
        return 'telegram_request_limiter';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $now = new UTCDateTime();

        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['date'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => [],
                ],
                'value' => $now,
            ],
        ];
    }

    public function attributes(): array
    {
        return ['_id', 'chatId', 'inlineMessageId', 'method', 'date'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['chatId'], 'number'],
            [['date'], 'safe'],
            [['inlineMessageId', 'method'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     * @return RequestLimiterQuery the active query used by this AR class.
     */
    public static function find(): RequestLimiterQuery
    {
        return new RequestLimiterQuery(get_called_class());
    }
}
