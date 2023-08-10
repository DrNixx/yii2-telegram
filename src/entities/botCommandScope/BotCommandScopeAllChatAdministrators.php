<?php
namespace onix\telegram\entities\botCommandScope;

/**
 * Class BotCommandScopeAllChatAdministrators
 *
 * @link https://core.telegram.org/bots/api#botcommandscopeallchatadministrators
 *
 * @property-read string $type
 */
class BotCommandScopeAllChatAdministrators extends BotCommandScope
{
    public function __construct(array $config = [])
    {
        $config['type'] = 'all_chat_administrators';
        parent::__construct($config);
    }
}
