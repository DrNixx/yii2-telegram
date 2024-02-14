<?php

namespace onix\telegram\entities\chatMember;

use yii\helpers\ArrayHelper;

/**
 * Class ChatMemberOwner
 *
 * @link https://core.telegram.org/bots/api#chatmemberowner
 *
 * @property-read string $customTitle Custom title for this user
 * @property-read bool $isAnonymous True, if the user's presence in the chat is hidden
 */
class ChatMemberOwner extends ChatMember
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ArrayHelper::merge(parent::attributes(), [
            'isAnonymous',
            'customTitle',
        ]);
    }
}