<?php

namespace onix\telegram\entities\topics;


use onix\telegram\entities\Entity;

/**
 * Class ForumTopicCreated
 *
 * This object represents a service message about a new forum topic created in the chat.
 *
 * @link https://core.telegram.org/bots/api#forumtopiccreated
 *
 * @property-read string $name Name of the topic
 * @property-read int $iconColor Color of the topic icon in RGB format
 * @property-read string $iconCustomEmojiId Optional. Unique identifier of the custom emoji shown as the topic icon
 */
class ForumTopicCreated extends Entity
{
    public function attributes(): array
    {
        return ['name', 'iconColor', 'iconCustomEmojiId'];
    }
}
