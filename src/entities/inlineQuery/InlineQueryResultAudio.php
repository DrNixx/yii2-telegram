<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultAudio
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultaudio
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'audio_url' => '',
 *   'title' => '',
 *   'caption' => '',
 *   'performer' => '',
 *   'audio_duration' => 123,
 *   'reply_markup' => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @property string $audioUrl A valid URL for the audio file
 * @property string $title Title
 * @property string $caption Optional. Caption, 0-200 characters
 * @property string $performer Optional. Performer
 * @property int $audioDuration Optional. Audio duration in seconds
 */
class InlineQueryResultAudio extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['audioUrl', 'title', 'caption', 'performer', 'audioDuration']
        );
    }

    /**
     * InlineQueryResultAudio constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'audio';
        parent::__construct($config);
    }
}
