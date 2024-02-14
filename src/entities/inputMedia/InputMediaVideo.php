<?php
namespace onix\telegram\entities\inputMedia;

use onix\telegram\entities\Entity;
use yii\helpers\ArrayHelper;

/**
 * Class InputMediaVideo
 *
 * @link https://core.telegram.org/bots/api#inputmediavideo
 *
 * <code>
 * $data = [
 *   'media' => '123abc',
 *   'thumb' => '456def',
 *   'caption' => '*Video* caption (streamable)',
 *   'parse_mode' => 'markdown',
 *   'width' => 800,
 *   'height' => 600,
 *   'duration' => 42,
 *   'supports_streaming' => true,
 * ];
 * </code>
 *
 * @property int $width Optional. Video width
 * @property int $height Optional. Video height
 * @property int $duration Optional. Video duration
 * @property bool $supportsStreaming Optional. Pass True, if the uploaded video is suitable for streaming
 */
class InputMediaVideo extends InputEntity implements InputMedia
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['width', 'height', 'duration', 'supportsStreaming']
        );
    }
    
    /**
     * InputMediaVideo constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'video';
        parent::__construct($config);
    }
}
