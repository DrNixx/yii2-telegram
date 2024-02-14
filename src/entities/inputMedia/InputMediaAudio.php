<?php
namespace onix\telegram\entities\inputMedia;

use yii\helpers\ArrayHelper;

/**
 * Class InputMediaAudio
 *
 * @link https://core.telegram.org/bots/api#inputmediaaudio
 *
 * <code>
 * $data = [
 *   'media' => '123abc',
 *   'thumb' => '456def',
 *   'caption' => '*Audio* caption',
 *   'parse_mode' => 'markdown',
 *   'duration' => 42,
 *   'performer' => 'John Doe',
 *   'title' => 'The Song',
 * ];
 * </code>
 *
 * @property int $duration Optional. Duration of the audio in seconds
 * @property string $performer Optional. Performer of the audio
 * @property string $title Optional. Title of the audio *
 */
class InputMediaAudio extends InputEntity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['duration', 'performer', 'title']
        );
    }
    
    /**
     * InputMediaAudio constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'audio';
        parent::__construct($config);
    }
}
