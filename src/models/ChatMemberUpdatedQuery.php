<?php
namespace onix\telegram\models;

use yii\mongodb\ActiveQuery;

class ChatMemberUpdatedQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return ChatMemberUpdated[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ChatMemberUpdated|array|null
     */
    public function one($db = null): ?ChatMemberUpdated
    {
        return parent::one($db);
    }
}