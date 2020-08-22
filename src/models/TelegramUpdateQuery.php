<?php
namespace onix\telegram\models;

use onix\data\ActiveQueryEx;

/**
 * This is the ActiveQuery class for [[TelegramUpdate]].
 *
 * @see TelegramUpdate
 */
class TelegramUpdateQuery extends ActiveQueryEx
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TelegramUpdate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TelegramUpdate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
