<?php
namespace onix\telegram\migrations\models_v1;

use onix\data\ActiveQueryEx;

/**
 * This is the ActiveQuery class for [[Message]].
 *
 * @see Message
 */
class MessageQuery extends ActiveQueryEx
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Message[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Message|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
