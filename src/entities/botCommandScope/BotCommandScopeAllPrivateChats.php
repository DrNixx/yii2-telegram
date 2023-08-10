<?php

namespace onix\telegram\entities\botCommandScope;

/**
 * Class BotCommandScopeAllPrivateChats
 *
 * @link https://core.telegram.org/bots/api#botcommandscopeallprivatechats
 */
class BotCommandScopeAllPrivateChats extends BotCommandScope
{
    public function __construct(array $config = [])
    {
        $config['type'] = 'all_private_chats';
        parent::__construct($config);
    }
}
