<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultCachedMpeg4Gif
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedmpeg4gif
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'mpeg4_file_id' => '',
 *   'title' => '',
 *   'caption' => '',
 *   'reply_markup' => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @property string $mpeg4FileId A valid file identifier for the MP4 file
 * @property string $title Optional. Title for the result
 * @property string $caption Optional. Caption of the MPEG-4 file to be sent, 0-200 characters
 */
class InlineQueryResultCachedMpeg4Gif extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['mpeg4FileId', 'title', 'caption']
        );
    }
    
    /**
     * InlineQueryResultCachedMpeg4Gif constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'mpeg4_gif';
        parent::__construct($config);
    }
}
