<?php
namespace onix\telegram\models;

use yii\mongodb\ActiveQuery;

/**
 * This is the ActiveQuery class for [[PreCheckoutQuery]].
 *
 * @see PreCheckoutQuery
 */
class PreCheckoutQueryQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return PreCheckoutQuery[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PreCheckoutQuery|array|null
     */
    public function one($db = null): ?PreCheckoutQuery
    {
        return parent::one($db);
    }
}
