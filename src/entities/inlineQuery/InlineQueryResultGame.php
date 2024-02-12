<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultGame
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultgame
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'game_short_name' => '',
 *   'reply_markup' => <InlineKeyboard>,
 * ];
 * </code>
 *
 * @property string $gameShortName Short name of the game
 */
class InlineQueryResultGame extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['gameShortName']
        );
    }

    /**
     * InlineQueryResultGame constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'game';
        parent::__construct($config);
    }
}
