<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultVoice
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultvoice
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'voice_url' => '',
 *   'title' => '',
 *   'caption' => '',
 *   'voice_duration' => 123,
 *   'reply_markup' => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @property string $voiceUrl A valid URL for the voice recording
 * @property string $title Recording title
 * @property string $caption Optional. Caption, 0-200 characters
 * @property int $voiceDuration Optional. Recording duration in seconds
 */
class InlineQueryResultVoice extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['voiceUrl', 'title', 'caption', 'voiceDuration']
        );
    }
    
    /**
     * InlineQueryResultVoice constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'voice';
        parent::__construct($config);
    }
}
