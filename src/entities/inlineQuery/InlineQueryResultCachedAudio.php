<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultCachedAudio
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedaudio
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'audio_file_id' => '',
 *   'caption' => '',
 *   'reply_markup' => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @method string getAudioFileId() A valid file identifier for the audio file
 * @method string getCaption() Optional. Caption, 0-200 characters
 */
class InlineQueryResultCachedAudio extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['audioFileId', 'caption']
        );
    }

    /**
     * InlineQueryResultCachedAudio constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'audio';
        parent::__construct($config);
    }
}
