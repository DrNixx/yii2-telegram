<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultMpeg4Gif
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultmpeg4gif
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'mpeg4_url' => '',
 *   'mpeg4_width' => 30,
 *   'mpeg4_height' => 30,
 *   'thumb_url' => '',
 *   'title' => '',
 *   'caption' => '',
 *   'reply_markup' => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @property string $mpeg4Url A valid URL for the MP4 file. File size must not exceed 1MB
 * @property int $mpeg4Width Optional. Video width
 * @property int $mpeg4Height Optional. Video height
 * @property int $mpeg4Duration Optional. Video duration
 * @property string $thumbUrl URL of the static thumbnail (jpeg or gif) for the result
 * @property string $title Optional. Title for the result
 * @property string $caption Optional. Caption of the MPEG-4 file to be sent, 0-200 characters
 */
class InlineQueryResultMpeg4Gif extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['mpeg4Url', 'mpeg4Width', 'mpeg4Height', 'mpeg4Duration', 'thumbUrl', 'title', 'caption']
        );
    }
    
    /**
     * InlineQueryResultMpeg4Gif constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'mpeg4_gif';
        parent::__construct($config);
    }
}
