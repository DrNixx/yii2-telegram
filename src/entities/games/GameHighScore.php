<?php
namespace onix\telegram\entities\games;

use onix\telegram\entities\Entity;
use onix\telegram\entities\User;

/**
 * Class GameHighScore
 *
 * This object represents one row of the high scores table for a game.
 *
 * @link https://core.telegram.org/bots/api#gamehighscore
 *
 * @property-read int $position Position in high score table for the game
 * @property-read User $user User
 * @property-read int $score Score
 **/
class GameHighScore extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['position', 'user', 'score'];
    }
    
    /**
     * @inheritDoc
     */
    protected function subEntities(): array
    {
        return [
            'user' => User::class,
        ];
    }
}
