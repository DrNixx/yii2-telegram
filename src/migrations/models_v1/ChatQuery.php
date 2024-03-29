<?php
namespace onix\telegram\migrations\models_v1;

use onix\data\ActiveQueryEx;

/**
 * This is the ActiveQuery class for [[Chat]].
 *
 * @see Chat
 */
class ChatQuery extends ActiveQueryEx
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Chat[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Chat|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
