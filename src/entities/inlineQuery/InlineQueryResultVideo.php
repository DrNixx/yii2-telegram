<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultVideo
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultvideo
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'video_url' => '',
 *   'mime_type' => '',
 *   'thumb_url' => '',
 *   'title' => '',
 *   'caption' => '',
 *   'video_width' => 30,
 *   'video_height' => 30,
 *   'video_duration' => 123,
 *   'description' => '',
 *   'reply_markup' => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @property string $videoUrl A valid URL for the embedded video player or video file
 * @property string $mimeType Mime type of the content of video url, "text/html " or "video/mp4 "
 * @property string $thumbUrl URL of the thumbnail (jpeg only) for the video
 * @property string $title Title for the result
 * @property string $caption Optional. Caption of the video to be sent, 0-200 characters
 * @property int $videoWidth Optional. Video width
 * @property int $videoHeight Optional. Video height
 * @property int $videoDuration Optional. Video duration in seconds
 * @property string $description Optional. Short description of the result
 */
class InlineQueryResultVideo extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(
            parent::attributes(),
            [
                'videoUrl',
                'mimeType',
                'thumbUrl',
                'title',
                'caption',
                'videoWidth',
                'videoHeight',
                'videoDuration',
                'description'
            ]
        );
    }
    
    /**
     * InlineQueryResultVideo constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'video';
        parent::__construct($config);
    }
}
