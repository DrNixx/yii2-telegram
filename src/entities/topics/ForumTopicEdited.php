<?php

namespace onix\telegram\entities\topics;

use onix\telegram\entities\Entity;

/**
 * Class ForumTopicEdited
 *
 * This object represents a service message about an edited forum topic.
 *
 * @link https://core.telegram.org/bots/api#forumtopicedited
 *
 * @property-read string $name Optional. New name of the topic, if it was edited
 * @property-read string $iconCustomEmojiId Optional. New identifier of the custom emoji shown as the topic icon,
 * if it was edited; an empty string if the icon was removed
 */
class ForumTopicEdited extends Entity
{
    public function attributes(): array
    {
        return ['name', 'iconCustomEmojiId'];
    }
}
