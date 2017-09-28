<?php

namespace Kutny\DateTimeBundle\Date;

use PHPUnit\Framework\TestCase;

class DateIntervalTest extends TestCase
{
    /** @test */
    public function getLengthInDays()
    {
        $dateInterval = new DateInterval(new Date(2013, 10, 5), new Date(2014, 10, 5));

        $this->assertSame(365, $dateInterval->getLengthInDays());
    }
}
