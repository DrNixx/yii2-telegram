<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultDocument
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultdocument
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'title' => '',
 *   'caption' => '',
 *   'document_url' => '',
 *   'mime_type' => '',
 *   'description' => '',
 *   'reply_markup' => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 *   'thumb_url' => '',
 *   'thumb_width' => 30,
 *   'thumb_height' => 30,
 * ];
 * </code>
 *
 * @property string $title Title for the result
 * @property string $caption Optional. Caption of the document to be sent, 0-200 characters
 * @property string $documentUrl A valid URL for the file
 * @property string $mimeType Mime type of the content of the file, either "application/pdf " or "application/zip "
 * @property string $description Optional. Short description of the result
 * @property string $thumbUrl Optional. URL of the thumbnail (jpeg only) for the file
 * @property int $thumbWidth Optional. Thumbnail width
 * @property int $thumbHeight Optional. Thumbnail height
 */
class InlineQueryResultDocument extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['title', 'caption', 'documentUrl', 'mimeType', 'description', 'thumbUrl', 'thumbWidth', 'thumbHeight']
        );
    }
    
    /**
     * InlineQueryResultDocument constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'document';
        parent::__construct($config);
    }
}
