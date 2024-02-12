<?php
namespace onix\telegram\models;

use yii\mongodb\ActiveQuery;

/**
 * This is the ActiveQuery class for [[ChosenInlineResult]].
 *
 * @see ChosenInlineResult
 */
class ChosenInlineResultQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return ChosenInlineResult[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ChosenInlineResult|array|null
     */
    public function one($db = null): ?ChosenInlineResult
    {
        return parent::one($db);
    }
}
