<?php

namespace onix\telegram\entities\messageOrigin;

use onix\telegram\entities\Entity;

/**
 * This object describes the origin of a message. It can be one of
 * - {@see MessageOriginUser}
 * - {@see MessageOriginHiddenUser}
 * - {@see MessageOriginChat}
 * - {@see MessageOriginChannel}
 * @link https://core.telegram.org/bots/api#messageorigin
 *
 * @property-read string $type Type of the message origin
 */
abstract class MessageOrigin extends Entity
{
    public function attributes(): array
    {
        return ['type'];
    }
}
