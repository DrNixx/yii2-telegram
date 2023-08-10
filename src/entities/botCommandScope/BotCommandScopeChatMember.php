<?php

namespace onix\telegram\entities\botCommandScope;

use yii\helpers\ArrayHelper;

/**
 * Class BotCommandScopeChatMember
 *
 * @link https://core.telegram.org/bots/api#botcommandscopechatmember
 *
 * <code>
 * $data = [
 *   'chat_id' => '123456',
 *   'user_id' => 987654,
 * ];
 * </code>
 *
 * @property string chatId Unique identifier for the target chat or username of the target supergroup
 * (in the format @supergroupusername)
 *
 * @property int userId Unique identifier of the target user
 */
class BotCommandScopeChatMember extends BotCommandScope
{
    public function __construct(array $config = [])
    {
        $config['type'] = 'chat_member';
        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), ['chatId', 'userId']);
    }
}
