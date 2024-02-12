<?php
namespace onix\telegram\entities\inputMedia;

use yii\helpers\ArrayHelper;

/**
 * Class InputMediaAnimation
 *
 * @link https://core.telegram.org/bots/api#inputmediaanimation
 *
 * <code>
 * $data = [
 *   'media' => '123abc',
 *   'thumb' => '456def',
 *   'caption' => '*Animation* caption',
 *   'parse_mode' => 'markdown',
 *   'width' => 200,
 *   'height' => 150,
 *   'duration' => 11,
 * ];
 * </code>
 *
 * @property int $width Optional. Animation width
 * @property int $height Optional. Animation height
 * @property int $duration Optional. Animation duration
 */
class InputMediaAnimation extends InputEntity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['width', 'height', 'duration']
        );
    }
    
    /**
     * InputMediaAnimation constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'animation';
        parent::__construct($config);
    }
}
