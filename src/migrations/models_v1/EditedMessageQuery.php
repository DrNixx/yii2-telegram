<?php
namespace onix\telegram\migrations\models_v1;

use onix\data\ActiveQueryEx;

/**
 * This is the ActiveQuery class for [[EditedMessage]].
 *
 * @see EditedMessage
 */
class EditedMessageQuery extends ActiveQueryEx
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return EditedMessage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return EditedMessage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
