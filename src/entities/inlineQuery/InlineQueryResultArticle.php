<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultArticle
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultarticle
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'title' => '',
 *   'input_message_content' => <InputMessageContent>,
 *   'reply_markup' => <InlineKeyboard>,
 *   'url' => '',
 *   'hide_url' => true,
 *   'description' => '',
 *   'thumb_url' => '',
 *   'thumb_width' => 30,
 *   'thumb_height' => 30,
 * ];
 * </code>
 *
 * @property string $title Title of the result
 * @property string $url Optional. URL of the result
 * @property bool $hideUrl Optional. Pass True, if you don't want the URL to be shown in the message
 * @property string $description Optional. Short description of the result
 * @property string $thumbUrl Optional. Url of the thumbnail for the result
 * @property int $thumbWidth Optional. Thumbnail width
 * @property int $thumbHeight Optional. Thumbnail height
 */
class InlineQueryResultArticle extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(
            parent::attributes(),
            [
                'title',
                'url',
                'hideUrl',
                'description',
                'thumbUrl',
                'thumbWidth',
                'thumbHeight'
            ]
        );
    }

    /**
     * InlineQueryResultArticle constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'article';
        parent::__construct($config);
    }
}
