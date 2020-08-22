<?php
namespace onix\telegram\models;

use onix\data\ActiveQueryEx;

/**
 * This is the ActiveQuery class for [[PollAnswer]].
 *
 * @see PollAnswer
 */
class PollAnswerQuery extends ActiveQueryEx
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PollAnswer[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PollAnswer|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
