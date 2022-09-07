<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultLocation
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultlocation
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'latitude' => 36.0338,
 *   'longitude' => 71.8601,
 *   'title' => '',
 *   'live_period' => 900,
 *   'reply_markup' => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 *   'thumb_url' => '',
 *   'thumb_width' => 30,
 *   'thumb_height' => 30,
 * ];
 * </code>
 *
 * @property float $latitude Location latitude in degrees
 * @property float $longitude Location longitude in degrees
 * @property string $title Location title
 * @property int $livePeriod Optional. Period in seconds for which the location can be updated,
 * should be between 60 and 86400.
 *
 * @property string $thumbUrl Optional. Url of the thumbnail for the result
 * @property int $thumbWidth Optional. Thumbnail width
 * @property int $thumbHeight Optional. Thumbnail height
 */
class InlineQueryResultLocation extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['latitude', 'longitude', 'title', 'livePeriod', 'thumbUrl', 'thumbWidth', 'thumbHeight']
        );
    }
    
    /**
     * InlineQueryResultLocation constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'location';
        parent::__construct($config);
    }
}
