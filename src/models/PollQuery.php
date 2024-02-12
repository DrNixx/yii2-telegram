<?php
namespace onix\telegram\models;

use yii\mongodb\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Poll]].
 *
 * @see Poll
 */
class PollQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Poll[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Poll|array|null
     */
    public function one($db = null): ?Poll
    {
        return parent::one($db);
    }
}
