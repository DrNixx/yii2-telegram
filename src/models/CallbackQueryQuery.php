<?php
namespace onix\telegram\models;

use yii\mongodb\ActiveQuery;

/**
 * This is the ActiveQuery class for [[CallbackQuery]].
 *
 * @see CallbackQuery
 */
class CallbackQueryQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return CallbackQuery[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CallbackQuery|array|null
     */
    public function one($db = null): CallbackQuery|array|null
    {
        return parent::one($db);
    }
}
