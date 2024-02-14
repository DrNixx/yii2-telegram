<?php
namespace onix\telegram\migrations\models_v1;

use onix\data\ActiveQueryEx;

/**
 * This is the ActiveQuery class for [[ShippingQuery]].
 *
 * @see ShippingQuery
 */
class ShippingQueryQuery extends ActiveQueryEx
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ShippingQuery[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ShippingQuery|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
