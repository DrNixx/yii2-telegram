<?php

namespace onix\telegram\entities\reaction;

/**
 * The reaction is based on a custom emoji.
 * @link https://core.telegram.org/bots/api#reactiontypecustomemoji
 *
 * @property-read string $customEmojiId Custom emoji identifier
 */
class ReactionTypeCustomEmoji extends ReactionType
{
    public function attributes(): array
    {
        return ['type', 'customEmojiId'];
    }

    public function __construct($config)
    {
        $config['type'] = 'custom_emoji';
        parent::__construct($config);
    }
}
