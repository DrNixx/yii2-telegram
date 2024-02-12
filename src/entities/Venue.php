<?php
namespace onix\telegram\entities;

/**
 * Class Venue
 *
 * @link https://core.telegram.org/bots/api#venue
 *
 * @property-read Location $location Venue location
 * @property-read string $litle Name of the venue
 * @property-read string $address Address of the venue
 * @property-read string $foursquareId Optional. Foursquare identifier of the venue
 * @property-read string $foursquareType Optional. Foursquare type of the venue. (For example,
 * "arts_entertainment/default", "arts_entertainment/aquarium" or "food/icecream".)
 */
class Venue extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['location', 'title', 'address', 'foursquareId', 'foursquareType'];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities(): array
    {
        return [
            'location' => Location::class,
        ];
    }
}
