<?php

namespace Kutny\DateTimeBundle;

use Kutny\DateTimeBundle\Date\Date;
use Kutny\DateTimeBundle\Time\Time;
use PHPUnit\Framework\TestCase;

class DateTimeFactoryTest extends TestCase
{
    const TIMEZONE_GMT = 'GMT';
    const TIMEZONE_PRAGUE = 'Europe/Prague';
    const TIMEZONE_LOS_ANGELES = 'America/Los_Angeles';

    /** @var DateTimeFactory */
    private $dateTimeFactory;

    protected function setUp()
    {
        $this->dateTimeFactory = new DateTimeFactory(self::TIMEZONE_GMT);
    }

    /** @test */
    public function fromFormat_validSameTimezone()
    {
        $dateTime = $this->dateTimeFactory->fromFormatDateTime('1987-07-31 11:19:59');

        $this->assertSame(1987, $dateTime->getDate()->getYear());
        $this->assertSame(7, $dateTime->getDate()->getMonth());
        $this->assertSame(31, $dateTime->getDate()->getDay());
        $this->assertSame(11, $dateTime->getTime()->getHour());
        $this->assertSame(19, $dateTime->getTime()->getMinute());
        $this->assertSame(59, $dateTime->getTime()->getSecond());
    }

    /** @test */
    public function fromFormat_validDifferentTimezone()
    {
        $dateTime = $this->dateTimeFactory->fromFormatDateTime('1987-07-31 11:19:59', self::TIMEZONE_PRAGUE);

        $this->assertSame(1987, $dateTime->getDate()->getYear());
        $this->assertSame(7, $dateTime->getDate()->getMonth());
        $this->assertSame(31, $dateTime->getDate()->getDay());
        $this->assertSame(9, $dateTime->getTime()->getHour());
        $this->assertSame(19, $dateTime->getTime()->getMinute());
        $this->assertSame(59, $dateTime->getTime()->getSecond());
    }

    /** @test */
    public function fromFormat_validDateOnly()
    {
        $date = $this->dateTimeFactory->fromFormatDate('1987-07-31');

        $this->assertSame(1987, $date->getYear());
        $this->assertSame(7, $date->getMonth());
        $this->assertSame(31, $date->getDay());
    }

    /** @test */
    public function fromFormat_timezoneInFormat()
    {
        $dateTime = $this->dateTimeFactory->fromFormat('H:i:s M d, Y T', '06:00:09 Dec 24, 2014 PST', self::TIMEZONE_LOS_ANGELES);

        $this->assertSame(2014, $dateTime->getDate()->getYear());
        $this->assertSame(12, $dateTime->getDate()->getMonth());
        $this->assertSame(24, $dateTime->getDate()->getDay());
        $this->assertSame(14, $dateTime->getTime()->getHour());
        $this->assertSame(0, $dateTime->getTime()->getMinute());
        $this->assertSame(9, $dateTime->getTime()->getSecond());
    }

    /** @test */
    public function fromFormat_timezoneInFormatInvalid()
    {
        try {
            $this->dateTimeFactory->fromFormat('H:i:s M d, Y T', '06:00:09 Dec 24, 2014 PST', self::TIMEZONE_GMT);

            $this->fail('Exception must be thrown here');
        }
        catch (\InvalidArgumentException $e) {
            // PST != London timezone
            $this->assertSame('dateTime timezone do NOT match sourceTimezone', $e->getMessage());
        }
    }

    /** @test */
    public function fromFormat_timezoneInFormatIso8601_winterTime()
    {
        $dateTime = $this->dateTimeFactory->fromFormat(DATE_ISO8601, '2017-11-15T04:10:30+01:00', self::TIMEZONE_PRAGUE);

        $this->assertSame(2017, $dateTime->getDate()->getYear());
        $this->assertSame(11, $dateTime->getDate()->getMonth());
        $this->assertSame(15, $dateTime->getDate()->getDay());
        $this->assertSame(3, $dateTime->getTime()->getHour());
        $this->assertSame(10, $dateTime->getTime()->getMinute());
        $this->assertSame(30, $dateTime->getTime()->getSecond());
    }

    /** @test */
    public function fromFormat_timezoneInFormatIso8601_summerTime()
    {
        $dateTime = $this->dateTimeFactory->fromFormat(DATE_ISO8601, '2017-06-15T04:10:30+02:00', self::TIMEZONE_PRAGUE);

        $this->assertSame(2017, $dateTime->getDate()->getYear());
        $this->assertSame(6, $dateTime->getDate()->getMonth());
        $this->assertSame(15, $dateTime->getDate()->getDay());
        $this->assertSame(2, $dateTime->getTime()->getHour());
        $this->assertSame(10, $dateTime->getTime()->getMinute());
        $this->assertSame(30, $dateTime->getTime()->getSecond());
    }

    /** @test */
    public function fromFormat_timezoneInFormatIso8601Invalid()
    {
        try {
            $this->dateTimeFactory->fromFormat(DATE_ISO8601, '2015-11-15T04:10:30+01:00', self::TIMEZONE_GMT);

            $this->fail('Exception must be thrown here');
        }
        catch (\InvalidArgumentException $e) {
            // PST != London timezone
            $this->assertSame('dateTime timezone do NOT match sourceTimezone', $e->getMessage());
        }
    }

    /** @test */
    public function fromFormat_timezoneInFormatIso8601v2()
    {
        $dateTime = $this->dateTimeFactory->fromFormat(DATE_ISO8601, '2012-01-02T13:30:56Z', self::TIMEZONE_GMT);

        $this->assertSame(2012, $dateTime->getDate()->getYear());
        $this->assertSame(1, $dateTime->getDate()->getMonth());
        $this->assertSame(2, $dateTime->getDate()->getDay());
        $this->assertSame(13, $dateTime->getTime()->getHour());
        $this->assertSame(30, $dateTime->getTime()->getMinute());
        $this->assertSame(56, $dateTime->getTime()->getSecond());
    }

    /** @test */
    public function fromFormat_invalidWithTime()
    {
        try {
            $this->dateTimeFactory->fromFormatDate('1987-07-31 11:19:59');

            $this->fail('Exception must be thrown here');
        }
        catch (\InvalidArgumentException $e) {
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function fromFormat_invalid()
    {
        try {
            $this->dateTimeFactory->fromFormatDateTime('Yvonne Strahovski');

            $this->fail('Exception must be thrown here');
        }
        catch (\InvalidArgumentException $e) {
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function fromFormatWithTimezone_winterTime()
    {
        $dateTime = $this->dateTimeFactory->fromFormatWithTimezone(DATE_ISO8601, '2015-11-15T04:10:30+01:00');

        $this->assertSame(2015, $dateTime->getDate()->getYear());
        $this->assertSame(11, $dateTime->getDate()->getMonth());
        $this->assertSame(15, $dateTime->getDate()->getDay());
        $this->assertSame(3, $dateTime->getTime()->getHour());
        $this->assertSame(10, $dateTime->getTime()->getMinute());
        $this->assertSame(30, $dateTime->getTime()->getSecond());
    }

    /** @test */
    public function fromFormatWithTimezone_summerTime()
    {
        $dateTime = $this->dateTimeFactory->fromFormatWithTimezone(DATE_ISO8601, '2017-05-06T11:12:31+01:00');

        $this->assertSame(2017, $dateTime->getDate()->getYear());
        $this->assertSame(5, $dateTime->getDate()->getMonth());
        $this->assertSame(6, $dateTime->getDate()->getDay());
        $this->assertSame(10, $dateTime->getTime()->getHour());
        $this->assertSame(12, $dateTime->getTime()->getMinute());
        $this->assertSame(31, $dateTime->getTime()->getSecond());
    }

    /** @test */
    public function fromFormatWithTimezone_noTimezoneGiven()
    {
        try {
            $this->dateTimeFactory->fromFormatWithTimezone('Y-m-d\TH:i:s', '2015-11-15T04:10:30');

            $this->fail('Exception must be thrown here');
        }
        catch (\InvalidArgumentException $e) {
            $this->assertSame('No timezone given in $dateTimeString', $e->getMessage());
        }
    }

    /** @test */
    public function fromTimestamp()
    {
        $expectedDateTime = new DateTime(new Date(2009, 2, 13), new Time(23, 31, 30));
        $dateTime = $this->dateTimeFactory->fromTimestamp(1234567890, self::TIMEZONE_GMT);

        $this->assertEquals($expectedDateTime, $dateTime);
    }
}
