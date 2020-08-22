<?php
namespace onix\telegram\models;

use onix\data\ActiveQueryEx;

/**
 * This is the ActiveQuery class for [[InlineQuery]].
 *
 * @see InlineQuery
 */
class InlineQueryQuery extends ActiveQueryEx
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return InlineQuery[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return InlineQuery|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
