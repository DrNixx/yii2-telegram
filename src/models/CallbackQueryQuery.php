<?php
namespace onix\telegram\models;

use onix\data\ActiveQueryEx;

/**
 * This is the ActiveQuery class for [[CallbackQuery]].
 *
 * @see CallbackQuery
 */
class CallbackQueryQuery extends ActiveQueryEx
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return CallbackQuery[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CallbackQuery|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
