<?php
namespace onix\telegram\models;

use yii\mongodb\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Message]].
 *
 * @see Message
 */
class MessageQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Message[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Message|array|null
     */
    public function one($db = null): array|Message|null
    {
        return parent::one($db);
    }
}
