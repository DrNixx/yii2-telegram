<?php
namespace onix\telegram\entities\inputMediaContent;

use onix\telegram\entities\Entity;

/**
 * Class InputVenueMessageContent
 *
 * @link https://core.telegram.org/bots/api#inputvenuemessagecontent
 *
 * <code>
 * $data = [
 *   'latitude' => 36.0338,
 *   'longitude' => 71.8601,
 *   'title' => '',
 *   'address' => '',
 *   'foursquare_id' => '',
 *   'foursquare_type' => '',
 * ];
 * </code>
 *
 * @property float $latitude Latitude of the location in degrees
 * @property float $longitude Longitude of the location in degrees
 * @property string $title Name of the venue
 * @property string $address Address of the venue
 * @property string $foursquareId Optional. Foursquare identifier of the venue, if known
 * @property string $foursquareType Optional. Foursquare type of the venue, if known. (For example,
 * "arts_entertainment/default ", "arts_entertainment/aquarium " or "food/icecream ".)
 */
class InputVenueMessageContent extends Entity implements InputMessageContent
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['latitude', 'longitude', 'title', 'address', 'foursquareId', 'foursquareType'];
    }
}
