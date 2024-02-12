<?php
namespace onix\telegram\migrations\models_v1;

use onix\data\ActiveQueryEx;

/**
 * This is the ActiveQuery class for [[Conversation]].
 *
 * @see Conversation
 */
class ConversationQuery extends ActiveQueryEx
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Conversation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Conversation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
