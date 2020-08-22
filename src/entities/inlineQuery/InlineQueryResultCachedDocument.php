<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultCachedDocument
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcacheddocument
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'title' => '',
 *   'document_file_id' => '',
 *   'description' => '',
 *   'caption' => '',
 *   'reply_markup' => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @property string $title Title for the result
 * @property string $documentFileId A valid file identifier for the file
 * @property string $description Optional. Short description of the result
 * @property string $caption Optional. Caption of the document to be sent, 0-200 characters
 */
class InlineQueryResultCachedDocument extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['title', 'documentFileId', 'description', 'caption']
        );
    }
    
    /**
     * InlineQueryResultCachedDocument constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'document';
        parent::__construct($config);
    }
}
