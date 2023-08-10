<?php

namespace onix\telegram\entities\botCommandScope;

use onix\telegram\entities\Entity;

/**
 * Class BotCommandScope
 * This object represents the scope to which bot commands are applied. Currently, the following 7 scopes are supported:
 *
 * @property-read string $type
 */
abstract class BotCommandScope extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['type'];
    }
}
