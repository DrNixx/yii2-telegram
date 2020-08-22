<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultVenue
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultvenue
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'latitude' => 36.0338,
 *   'longitude' => 71.8601,
 *   'title' => '',
 *   'address' => '',
 *   'foursquare_id' => '',
 *   'reply_markup' => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 *   'thumb_url' => '',
 *   'thumb_width' => 30,
 *   'thumb_height' => 30,
 * ];
 * </code>
 *
 * @property float $latitude Latitude of the venue location in degrees
 * @property float $longitude Longitude of the venue location in degrees
 * @property string $title Title of the venue
 * @property string $address Address of the venue
 * @property string $foursquareId Optional. Foursquare identifier of the venue if known
 * @property string $foursquareType Optional. Foursquare type of the venue, if known.
 * (For example, "arts_entertainment/default ", "arts_entertainment/aquarium " or "food/icecream ".)
 *
 * @property string $thumbUrl Optional. Url of the thumbnail for the result
 * @property int $thumbWidth Optional. Thumbnail width
 * @property int $thumbHeight Optional. Thumbnail height
 */
class InlineQueryResultVenue extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(
            parent::attributes(),
            [
                'latitude',
                'longitude',
                'title',
                'address',
                'foursquareId',
                'foursquareType',
                'thumbUrl',
                'thumbWidth',
                'thumbHeight'
            ]
        );
    }
    
    /**
     * InlineQueryResultVenue constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'venue';
        parent::__construct($config);
    }
}
