<?php
namespace onix\telegram\entities\games;

use onix\telegram\entities\Animation;
use onix\telegram\entities\Entity;
use onix\telegram\entities\MessageEntity;
use onix\telegram\entities\PhotoSize;

/**
 * Class Game
 *
 * This object represents a game. Use BotFather to create and edit games,
 * their short names will act as unique identifiers.
 *
 * @link https://core.telegram.org/bots/api#game
 *
 * @property-read string $title Title of the game
 * @property-read string $description Description of the game
 * @property-read PhotoSize[] $photo Photo that will be displayed in the game message in chats.
 * @property-read string $text Optional. Brief description of the game or high scores included in the game message.
 * Can be automatically edited to include current high scores for the game when the bot calls setGameScore,
 * or manually edited using editMessageText. 0-4096 characters.
 *
 * @property-read MessageEntity[] $textEntities Optional. Special entities that appear in text, such as usernames,
 * URLs, bot commands, etc.
 *
 * @property-read Animation $animation() Optional. Animation that will be displayed in the game message in chats.
 * Upload via BotFather
 **/
class Game extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['title', 'description', 'photo', 'text', 'textEntities', 'animation'];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'photo' => [PhotoSize::class],
            'textEntities' => [MessageEntity::class],
            'animation' => Animation::class,
        ];
    }
}
