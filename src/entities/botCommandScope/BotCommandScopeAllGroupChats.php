<?php

namespace onix\telegram\entities\botCommandScope;

/**
 * Class BotCommandScopeAllGroupChats
 *
 * @link https://core.telegram.org/bots/api#botcommandscopeallgroupchats
 */
class BotCommandScopeAllGroupChats extends BotCommandScope
{
    public function __construct(array $config = [])
    {
        $config['type'] = 'all_group_chats';
        parent::__construct($config);
    }
}