<?php
namespace onix\telegram\entities;

/**
 * Class Location
 *
 * @link https://core.telegram.org/bots/api#location
 *
 * @property-read float longitude Longitude as defined by sender
 * @property-read float latitude Latitude as defined by sender
 */
class Location extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['longitude', 'latitude'];
    }
}
