<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultCachedVideo
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedvideo
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'video_file_id' => '',
 *   'title' => '',
 *   'description' => '',
 *   'caption' => '',
 *   'reply_markup' => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @property string $videoFileId A valid file identifier for the video file
 * @property string $title Title for the result
 * @property string $description Optional. Short description of the result
 * @property string $caption Optional. Caption of the video to be sent, 0-200 characters
 */
class InlineQueryResultCachedVideo extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['videoFileId', 'title', 'description', 'caption']
        );
    }
    
    /**
     * InlineQueryResultCachedVideo constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'video';
        parent::__construct($config);
    }
}
