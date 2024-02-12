<?php
namespace onix\telegram\models;

use yii\mongodb\ActiveQuery;

/**
 * This is the ActiveQuery class for User.
 *
 * @see User
 */
class UserQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return User[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return User|array|null
     */
    public function one($db = null): User|array|null
    {
        return parent::one($db);
    }
}
