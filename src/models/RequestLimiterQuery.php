<?php
namespace onix\telegram\models;

use yii\mongodb\ActiveQuery;

/**
 * This is the ActiveQuery class for [[RequestLimiter]].
 *
 * @see RequestLimiter
 */
class RequestLimiterQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return RequestLimiter[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RequestLimiter|array|null
     */
    public function one($db = null): ?RequestLimiter
    {
        return parent::one($db);
    }
}
