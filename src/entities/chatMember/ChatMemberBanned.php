<?php

namespace onix\telegram\entities\chatMember;

use yii\helpers\ArrayHelper;

/**
 * Class ChatMemberBanned
 *
 * @link https://core.telegram.org/bots/api#chatmemberbanned
 *
 * @property-read int $untilDate Date when restrictions will be lifted for this user; unix time
 */
class ChatMemberBanned extends ChatMember
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ArrayHelper::merge(parent::attributes(), ['untilDate']);
    }
}
