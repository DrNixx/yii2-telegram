<?php
namespace onix\telegram\models;

use yii\mongodb\ActiveQuery;

/**
 * This is the ActiveQuery class for [[TelegramUpdate]].
 *
 * @see TelegramUpdate
 */
class TelegramUpdateQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TelegramUpdate[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TelegramUpdate|array|null
     */
    public function one($db = null): TelegramUpdate|array|null
    {
        return parent::one($db);
    }
}
