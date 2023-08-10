<?php

namespace onix\telegram\entities\botCommandScope;

use yii\helpers\ArrayHelper;

/**
 * Class BotCommandScopeChatAdministrators
 *
 * @link https://core.telegram.org/bots/api#botcommandscopechatadministrators
 *
 * <code>
 * $data = [
 *   'chat_id' => '123456'
 * ];
 * </code>
 *
 * @property string chatId Unique identifier for the target chat or username of the target supergroup
 * (in the format @supergroupusername)
 */
class BotCommandScopeChatAdministrators extends BotCommandScope
{
    public function __construct(array $config = [])
    {
        $config['type'] = 'chat_administrators';
        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), ['chatId']);
    }
}
