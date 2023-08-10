<?php

namespace onix\telegram\entities\botCommandScope;

/**
 * Class BotCommandScopeDefault
 *
 * @link https://core.telegram.org/bots/api#botcommandscopedefault
 */
class BotCommandScopeDefault extends BotCommandScope
{
    public function __construct(array $config = [])
    {
        $config['type'] = 'default';
        parent::__construct($config);
    }
}
