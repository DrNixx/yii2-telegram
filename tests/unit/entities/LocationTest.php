<?php
namespace onix\telegram\tests\unit\entities;

use onix\telegram\entities\Location;

class LocationTest extends \Codeception\Test\Unit
{
    private $coordinates;

    public function setUp(): void
    {
        $this->coordinates = [
            'longitude' => (float) mt_rand(10, 69),
            'latitude'  => (float) mt_rand(10, 48),
        ];
    }

    public function testBaseStageLocation()
    {
        $location = new Location($this->coordinates);
        $this->assertInstanceOf(Location::class, $location);
    }

    public function testGetLongitude()
    {
        $location = new Location($this->coordinates);
        $long     = $location->longitude;
        $this->assertIsFloat($long);
        $this->assertEquals($this->coordinates['longitude'], $long);
    }

    public function testGetLatitude()
    {
        $location = new Location($this->coordinates);
        $lat      = $location->latitude;
        $this->assertIsFloat($lat);
        $this->assertEquals($this->coordinates['latitude'], $lat);
    }
}
