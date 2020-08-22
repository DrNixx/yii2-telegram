<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultCachedSticker
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedsticker
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'sticker_file_id' => '',
 *   'reply_markup' => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @property string stickerFileId A valid file identifier of the sticker
 */
class InlineQueryResultCachedSticker extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['stickerFileId']
        );
    }
    
    /**
     * InlineQueryResultCachedSticker constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'sticker';
        parent::__construct($config);
    }
}
