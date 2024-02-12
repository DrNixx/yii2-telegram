<?php
namespace onix\telegram\entities\inputMediaContent;

use onix\telegram\entities\Entity;

/**
 * Class InputLocationMessageContent
 *
 * @link https://core.telegram.org/bots/api#inputlocationmessagecontent
 *
 * <code>
 * $data = [
 *   'latitude' => 36.0338,
 *   'longitude' => 71.8601,
 *   'live_period' => 900,
 * ];
 *
 * @property float $latitude Latitude of the location in degrees
 * @property float $longitude Longitude of the location in degrees
 * @property int $livePeriod Optional. Period in seconds for which the location can be updated, should be
 * between 60 and 86400.
 */
class InputLocationMessageContent extends Entity implements InputMessageContent
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['latitude', 'longitude', 'livePeriod'];
    }
}
