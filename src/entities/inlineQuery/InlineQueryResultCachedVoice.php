<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultCachedVoice
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedvoice
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'voice_file_id' => '',
 *   'title' => '',
 *   'caption' => '',
 *   'reply_markup' => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @property string $voiceFileId A valid file identifier for the voice message
 * @property string $title Voice message title
 * @property string $caption Optional. Caption, 0-200 characters
 */
class InlineQueryResultCachedVoice extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['voiceFileId', 'title', 'caption']
        );
    }
    
    /**
     * InlineQueryResultCachedVoice constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'voice';
        parent::__construct($config);
    }
}
