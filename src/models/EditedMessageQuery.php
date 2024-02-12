<?php
namespace onix\telegram\models;

use yii\mongodb\ActiveQuery;

/**
 * This is the ActiveQuery class for [[EditedMessage]].
 *
 * @see EditedMessage
 */
class EditedMessageQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return EditedMessage[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return EditedMessage|array|null
     */
    public function one($db = null): ?EditedMessage
    {
        return parent::one($db);
    }
}
