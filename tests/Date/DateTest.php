<?php

namespace Kutny\DateTimeBundle\Date;

use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{
    /** @test */
    public function toTimestamp()
    {
        $date = new Date(2017, 9, 28);

        $this->assertSame(1506556800, $date->toTimestamp());
    }

    /**
     * @test
     * @dataProvider getDaysFrom_dataProvider
     */
    public function getDaysFrom(Date $startDate, Date $endDate, $expectedDaysDifference)
    {
        $daysFrom = $endDate->getDaysFrom($startDate);

        $this->assertSame($expectedDaysDifference, $daysFrom);
    }

    /** @test */
    public function addDays_withTimechange()
    {
        $dateTime = new Date(2010, 10, 31);

        $newDateTime = $dateTime->addDays(1);

        $expectedNewDateTime = new Date(2010, 11, 1);

        $this->assertEquals($expectedNewDateTime, $newDateTime);
    }

    /** @test */
    public function subDays()
    {
        $dateTime = new Date(1987, 7, 31);

        $newDateTime = $dateTime->subDays(15);

        $expectedNewDateTime = new Date(1987, 7, 16);

        $this->assertEquals($expectedNewDateTime, $newDateTime);
    }

    /** @test */
    public function addMonths()
    {
        $dateTime = new Date(2014, 1, 31);

        $newDateTime = $dateTime->addMonths(1);

        $expectedNewDateTime = new Date(2014, 3, 3);

        $this->assertEquals($expectedNewDateTime, $newDateTime);
    }

    /** @test */
    public function subMonths()
    {
        $dateTime = new Date(2010, 11, 1);

        $newDateTime = $dateTime->subMonths(5);

        $expectedNewDateTime = new Date(2010, 6, 1);

        $this->assertEquals($expectedNewDateTime, $newDateTime);
    }

    public function getDaysFrom_dataProvider()
    {
        return [
            [new Date(2014, 1, 11), new Date(2014, 1, 28), 17],
            [new Date(2014, 9, 20), new Date(2015, 1, 2), 104], // time change (summer time -> winter time) occured
            [new Date(2014, 1, 28), new Date(2014, 1, 11), -17],
            [new Date(2016, 3, 26), new Date(2016, 3, 28), 2], // time change (winter time -> summer time) occured
            [new Date(2015, 1, 1), new Date(2016, 1, 1), 365],
            [new Date(2016, 1, 1), new Date(2017, 1, 1), 366],
        ];
    }
}
