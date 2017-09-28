<?php

namespace Kutny\DateTimeBundle;

use Kutny\DateTimeBundle\Date\Date;
use Kutny\DateTimeBundle\Time\Time;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    /** @test */
    public function getters()
    {
        $dateTime = new DateTime(
            new Date('1987', '07', '31'),
            new Time('11', '19', '59')
        );

        $this->assertSame(1987, $dateTime->getDate()->getYear());
        $this->assertSame(7, $dateTime->getDate()->getMonth());
        $this->assertSame(31, $dateTime->getDate()->getDay());
        $this->assertSame(11, $dateTime->getTime()->getHour());
        $this->assertSame(19, $dateTime->getTime()->getMinute());
        $this->assertSame(59, $dateTime->getTime()->getSecond());
    }

    /** @test */
    public function toFormat()
    {
        $dateTime = new DateTime(
            new Date('1987', '07', '31'),
            new Time('11', '19', '59.57')
        );

        $this->assertEquals('1987-07-31 11:19:59', $dateTime->toFormat('Y-m-d H:i:s'));
    }

    /**
     * @test
     * @dataProvider addWorkingDaysDataProvider
     */
    public function addWorkingDays(DateTime $inDate, DateTime $outExpectedDate, $move)
    {
        $this->assertEquals($outExpectedDate, $inDate->addWorkingDays($move));
    }

    public function addWorkingDaysDataProvider()
    {
        return [
            [new DateTime(new Date(2013, 6, 20), new Time(11, 19, 59)), new DateTime(new Date(2013, 6, 21), new Time(11, 19, 59)), 1],
            [new DateTime(new Date(2013, 6, 21), new Time(11, 19, 59)), new DateTime(new Date(2013, 6, 24), new Time(11, 19, 59)), 1],
            [new DateTime(new Date(2013, 6, 22), new Time(11, 19, 59)), new DateTime(new Date(2013, 6, 24), new Time(11, 19, 59)), 1],
            [new DateTime(new Date(2013, 6, 23), new Time(11, 19, 59)), new DateTime(new Date(2013, 6, 25), new Time(11, 19, 59)), 1],
            [new DateTime(new Date(2013, 6, 24), new Time(11, 19, 59)), new DateTime(new Date(2013, 6, 25), new Time(11, 19, 59)), 1],
            [new DateTime(new Date(2013, 6, 24), new Time(11, 19, 59)), new DateTime(new Date(2013, 6, 28), new Time(11, 19, 59)), 4],
            [new DateTime(new Date(2013, 6, 23), new Time(11, 19, 59)), new DateTime(new Date(2013, 6, 28), new Time(11, 19, 59)), 4],
            [new DateTime(new Date(2013, 6, 22), new Time(11, 19, 59)), new DateTime(new Date(2013, 6, 28), new Time(11, 19, 59)), 4],
            [new DateTime(new Date(2013, 6, 21), new Time(11, 19, 59)), new DateTime(new Date(2013, 6, 27), new Time(11, 19, 59)), 4],
            [new DateTime(new Date(2013, 6, 24), new Time(11, 19, 59)), new DateTime(new Date(2013, 7, 1), new Time(11, 19, 59)), 5],
            [new DateTime(new Date(2013, 6, 24), new Time(11, 19, 59)), new DateTime(new Date(2013, 7, 8), new Time(11, 19, 59)), 10],
            [new DateTime(new Date(2013, 6, 24), new Time(11, 19, 59)), new DateTime(new Date(2013, 7, 15), new Time(11, 19, 59)), 15],
            [new DateTime(new Date(2013, 6, 24), new Time(11, 19, 59)), new DateTime(new Date(2013, 7, 22), new Time(11, 19, 59)), 20],
            [new DateTime(new Date(2013, 6, 24), new Time(11, 19, 59)), new DateTime(new Date(2013, 7, 29), new Time(11, 19, 59)), 25],
        ];
    }

    /** @test */
    public function toTimestamp()
    {
        $dateTime = new DateTime(new Date(2009, 2, 13), new Time(23, 31, 30));

        $this->assertSame(
            1234567890,
            $dateTime->toTimestamp()
        );
    }

    /**
     * @test
     * @dataProvider isBetweenProvider
     */
    public function isBetween(DateTime $now, DateTime $start, DateTime $end, $expected)
    {
        $this->assertSame($expected, $now->isBetween($start, $end));
    }

    public function isBetweenProvider()
    {
        return [
            [new DateTime(new Date(2012, 5, 5), new Time(4, 5, 6)), new DateTime(new Date(2011, 5, 5), new Time(4, 5, 6)), new DateTime(new Date(2013, 5, 5), new Time(4, 5, 6)), true],
            [new DateTime(new Date(2015, 5, 5), new Time(4, 5, 6)), new DateTime(new Date(2011, 5, 5), new Time(4, 5, 6)), new DateTime(new Date(2013, 5, 5), new Time(4, 5, 6)), false],
            [new DateTime(new Date(2010, 5, 5), new Time(4, 5, 6)), new DateTime(new Date(2011, 5, 5), new Time(4, 5, 6)), new DateTime(new Date(2013, 5, 5), new Time(4, 5, 6)), false],
        ];
    }

    /** @test */
    public function subDays()
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 0)
        );

        $newDateTime = $dateTime->subDays(15);

        $expectedNewDateTime = new DateTime(
            new Date(1987, 7, 16),
            new Time(11, 19, 0)
        );

        $this->assertEquals($expectedNewDateTime, $newDateTime);
    }

    /** @test */
    public function subHours()
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 0)
        );

        $newDateTime = $dateTime->subHours(2);

        $expectedNewDateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(9, 19, 0)
        );

        $this->assertEquals($expectedNewDateTime, $newDateTime);
    }

    /** @test */
    public function subMinutes()
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 0)
        );

        $newDateTime = $dateTime->subMinutes(5);

        $expectedNewDateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 14, 0)
        );

        $this->assertEquals($expectedNewDateTime, $newDateTime);
    }

    /** @test */
    public function addDays()
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 0)
        );

        $newDateTime = $dateTime->addDays(15);

        $expectedNewDateTime = new DateTime(
            new Date(1987, 8, 15),
            new Time(11, 19, 0)
        );

        $this->assertEquals($expectedNewDateTime, $newDateTime);
    }

    /** @test */
    public function addHours()
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 0)
        );

        $newDateTime = $dateTime->addHours(2);

        $expectedNewDateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(13, 19, 0)
        );

        $this->assertEquals($expectedNewDateTime, $newDateTime);
    }

    /** @test */
    public function addMinutes()
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 0)
        );

        $newDateTime = $dateTime->addMinutes(80);

        $expectedNewDateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(12, 39, 0)
        );

        $this->assertEquals($expectedNewDateTime, $newDateTime);
    }
}
