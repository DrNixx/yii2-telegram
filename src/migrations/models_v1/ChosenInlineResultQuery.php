<?php
namespace onix\telegram\migrations\models_v1;

use onix\data\ActiveQueryEx;

/**
 * This is the ActiveQuery class for [[ChosenInlineResult]].
 *
 * @see ChosenInlineResult
 */
class ChosenInlineResultQuery extends ActiveQueryEx
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ChosenInlineResult[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ChosenInlineResult|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
