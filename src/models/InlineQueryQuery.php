<?php
namespace onix\telegram\models;

use yii\mongodb\ActiveQuery;

/**
 * This is the ActiveQuery class for [[InlineQuery]].
 *
 * @see InlineQuery
 */
class InlineQueryQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return InlineQuery[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return InlineQuery|array|null
     */
    public function one($db = null): ?InlineQuery
    {
        return parent::one($db);
    }
}
