<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultCachedPhoto
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedphoto
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'photo_file_id' => '',
 *   'title' => '',
 *   'description' => '',
 *   'caption' => '',
 *   'reply_markup' => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @property string $photoFileId A valid file identifier of the photo
 * @property string $title Optional. Title for the result
 * @property string $description Optional. Short description of the result
 * @property string $caption() Optional. Caption of the photo to be sent, 0-200 characters
 */
class InlineQueryResultCachedPhoto extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['photoFileId', 'title', 'description', 'caption']
        );
    }
    
    /**
     * InlineQueryResultCachedPhoto constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'photo';
        parent::__construct($config);
    }
}
