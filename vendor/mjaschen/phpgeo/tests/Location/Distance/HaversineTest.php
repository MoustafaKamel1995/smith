<?php

declare(strict_types=1);

namespace Location\Distance;

use Location\Ellipsoid;
use Location\Coordinate;
use PHPUnit\Framework\TestCase;

class HaversineTest extends TestCase
{
    protected $calculator;
    protected $ellipsoid;

    protected function setUp()
    {
        $ellipsoidConfig = [
            'name' => 'WGS-84',
            'a'    => 6378137.0,
            'f'    => 298.257223563,
        ];

        $this->ellipsoid = Ellipsoid::createFromArray($ellipsoidConfig);

        $this->calculator = new Haversine();
    }

    public function testGetDistanceZero()
    {
        $coordinate1 = new Coordinate(52.5, 13.5, $this->ellipsoid);
        $coordinate2 = new Coordinate(52.5, 13.5, $this->ellipsoid);

        $distance = $this->calculator->getDistance($coordinate1, $coordinate2);

        $this->assertEquals(0.0, $distance);
    }

    public function testGetDistanceSameLatitude()
    {
        $coordinate1 = new Coordinate(52.5, 13.5, $this->ellipsoid);
        $coordinate2 = new Coordinate(52.5, 13.1, $this->ellipsoid);

        $distance = $this->calculator->getDistance($coordinate1, $coordinate2);

        $this->assertEquals(27076.476, $distance);
    }

    public function testGetDistanceSameLongitude()
    {
        $coordinate1 = new Coordinate(52.5, 13.5, $this->ellipsoid);
        $coordinate2 = new Coordinate(52.1, 13.5, $this->ellipsoid);

        $distance = $this->calculator->getDistance($coordinate1, $coordinate2);

        $this->assertEquals(44478.032, $distance);
    }

    public function testGetDistance()
    {
        $coordinate1 = new Coordinate(19.820664, - 155.468066, $this->ellipsoid);
        $coordinate2 = new Coordinate(20.709722, - 156.253333, $this->ellipsoid);

        $distance = $this->calculator->getDistance($coordinate1, $coordinate2);

        $this->assertEquals(128384.515, $distance);
    }

    public function testGetDistanceInternationalDateLine()
    {
        $coordinate1 = new Coordinate(20.0, 170.0, $this->ellipsoid);
        $coordinate2 = new Coordinate(- 20.0, - 170.0, $this->ellipsoid);

        $distance = $this->calculator->getDistance($coordinate1, $coordinate2);

        $this->assertEquals(4952349.639, $distance);
    }

    /**
     * @expectedException \Location\Exception\NotMatchingEllipsoidException
     */
    public function testNotMatchingEllispoids()
    {
        $coordinate1 = new Coordinate(19.820664, - 155.468066, $this->ellipsoid);
        $coordinate2 = new Coordinate(20.709722, - 156.253333, new Ellipsoid('AnotherEllipsoid', 6378140.0, 299.2));

        $distance = $this->calculator->getDistance($coordinate1, $coordinate2);
    }
}