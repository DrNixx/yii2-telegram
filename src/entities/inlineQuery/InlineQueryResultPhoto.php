<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultPhoto
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultphoto
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'photo_url' => '',
 *   'thumb_url' => '',
 *   'photo_width' => 30,
 *   'photo_height' => 30,
 *   'title' => '',
 *   'description' => '',
 *   'caption' => '',
 *   'reply_markup' => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @property string $photoUrl A valid URL of the photo. Photo must be in jpeg format. Photo size must not exceed 5MB
 * @property string $thumbUrl URL of the thumbnail for the photo
 * @property int $photoWidth Optional. Width of the photo
 * @property int $photoHeight Optional. Height of the photo
 * @property string $title Optional. Title for the result
 * @property string $description Optional. Short description of the result
 * @property string $caption Optional. Caption of the photo to be sent, 0-200 characters
 * to be sent instead of the photo
 */
class InlineQueryResultPhoto extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['photoUrl', 'thumbUrl', 'photoWidth', 'photoHeight', 'title', 'description', 'caption']
        );
    }
    
    /**
     * InlineQueryResultPhoto constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'photo';
        parent::__construct($config);
    }
}
