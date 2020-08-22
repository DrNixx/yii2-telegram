<?php
namespace onix\telegram\models;

use onix\data\ActiveQueryEx;

/**
 * This is the ActiveQuery class for [[RequestLimiter]].
 *
 * @see RequestLimiter
 */
class RequestLimiterQuery extends ActiveQueryEx
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RequestLimiter[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RequestLimiter|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
