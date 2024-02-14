<?php

namespace onix\telegram\entities\botCommandScope;

use yii\helpers\ArrayHelper;

/**
 * Class BotCommandScopeChat
 *
 * @link https://core.telegram.org/bots/api#botcommandscopechat
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
class BotCommandScopeChat extends BotCommandScope
{
    public function __construct(array $config = [])
    {
        $config['type'] = 'chat';
        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ArrayHelper::merge(parent::attributes(), ['chatId']);
    }
}
