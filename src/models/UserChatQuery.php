<?php
namespace onix\telegram\models;

use yii\mongodb\ActiveQuery;

/**
 * This is the ActiveQuery class for [[UserChat]].
 *
 * @see UserChat
 */
class UserChatQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return UserChat[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserChat|array|null
     */
    public function one($db = null): ?UserChat
    {
        return parent::one($db);
    }
}
