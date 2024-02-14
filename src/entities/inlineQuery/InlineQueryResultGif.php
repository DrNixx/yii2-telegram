<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultGif
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultgif
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'gif_url' => '',
 *   'gif_width' => 30,
 *   'gif_height' => 30,
 *   'thumb_url' => '',
 *   'title' => '',
 *   'caption' => '',
 *   'reply_markup' => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @property string $gifUrl A valid URL for the GIF file. File size must not exceed 1MB
 * @property int $gifWidth Optional. Width of the GIF
 * @property int $gifHeight Optional. Height of the GIF
 * @property int $gifDuration Optional. Duration of the GIF
 * @property string $thumbUrl URL of the static thumbnail for the result (jpeg or gif)
 * @property string $title Optional. Title for the result
 * @property string $caption Optional. Caption of the GIF file to be sent, 0-200 characters
 */
class InlineQueryResultGif extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['gifUrl', 'gifWidth', 'gifHeight', 'gifDuration', 'thumbUrl', 'title', 'caption']
        );
    }
    
    /**
     * InlineQueryResultGif constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'gif';
        parent::__construct($config);
    }
}
