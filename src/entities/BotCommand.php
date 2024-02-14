<?php
namespace onix\telegram\entities;

/**
 * Class BotCommand
 *
 * This entity represents a bot command.
 *
 * @link https://core.telegram.org/bots/api#botcommand
 *
 * @property-read string $command Text of the command, 1-32 characters. Can contain only lowercase English letters,
 * digits and underscores.
 *
 * @property-read string $description Description of the command, 3-256 characters.
 */
class BotCommand extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['command', 'description'];
    }
}
