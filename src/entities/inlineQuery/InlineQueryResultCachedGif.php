<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultCachedGif
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedgif
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'gif_file_id' => '',
 *   'title' => '',
 *   'caption' => '',
 *   'reply_markup' => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @property string $gifFileId A valid file identifier for the GIF file
 * @property string $title Optional. Title for the result
 * @property string $caption Optional. Caption of the GIF file to be sent, 0-200 characters
 */
class InlineQueryResultCachedGif extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['gifFileId', 'title', 'caption']
        );
    }

    /**
     * InlineQueryResultCachedGif constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'gif';
        parent::__construct($config);
    }
}
