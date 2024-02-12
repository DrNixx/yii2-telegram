<?php
namespace onix\telegram\migrations\models_v1;

use onix\data\ActiveQueryEx;

class ChatMemberUpdatedQuery extends ActiveQueryEx
{
    /**
     * {@inheritdoc}
     * @return ChatMemberUpdated[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ChatMemberUpdated|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}