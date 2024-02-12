<?php
namespace onix\telegram\models;

use yii\mongodb\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Chat]].
 *
 * @see Chat
 */
class ChatQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Chat[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Chat|array|null
     */
    public function one($db = null): Chat|array|null
    {
        return parent::one($db);
    }
}
