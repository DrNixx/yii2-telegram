<?php
namespace onix\telegram\models;

use yii\mongodb\ActiveQuery;

/**
 * This is the ActiveQuery class for [[PollAnswer]].
 *
 * @see PollAnswer
 */
class PollAnswerQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return PollAnswer[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PollAnswer|array|null
     */
    public function one($db = null): ?PollAnswer
    {
        return parent::one($db);
    }
}
