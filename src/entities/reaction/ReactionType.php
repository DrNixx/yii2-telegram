<?php

namespace onix\telegram\entities\reaction;

use onix\telegram\entities\Entity;

/**
 * This object describes the type of a reaction. Currently, it can be one of:
 * - {@see ReactionTypeEmoji ReactionTypeEmoji}
 * - {@see ReactionTypeCustomEmoji ReactionTypeCustomEmoji}
 * @link https://core.telegram.org/bots/api#reactiontype
 *
 * @property-read string $type Type of the reaction
 */
abstract class ReactionType extends Entity
{
}
