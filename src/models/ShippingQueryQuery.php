<?php
namespace onix\telegram\models;

use yii\mongodb\ActiveQuery;

/**
 * This is the ActiveQuery class for [[ShippingQuery]].
 *
 * @see ShippingQuery
 */
class ShippingQueryQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return ShippingQuery[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ShippingQuery|array|null
     */
    public function one($db = null): ?ShippingQuery
    {
        return parent::one($db);
    }
}
