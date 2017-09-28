<?php

namespace Kutny\DateTimeBundle;

use Kutny\DateTimeBundle\Date\Date;
use Kutny\DateTimeBundle\Time\Time;
use DateTime as DateTimePhp;
use DateTimeZone;
use InvalidArgumentException;

class DateTimeFactory
{
    const TIMEZONE_GMT = 'GMT';
    const FORMAT_DATE_ONLY = 'Y-m-d';
    const FORMAT_DATETIME = 'Y-m-d H:i:s';

    private $appTimezone;

    public function __construct($appTimezone)
    {
        $this->appTimezone = $appTimezone;
    }

    public function now($timezone = self::TIMEZONE_GMT)
    {
        $timezonePhp = new DateTimeZone($timezone);
        $datetimePhp = new DateTimePhp('now', $timezonePhp);

        return $this->fromDateTimePhp($datetimePhp, $timezone);
    }

    public function fromTimestamp($timestamp, $timezone = self::TIMEZONE_GMT)
    {
        $timezonePhp = new DateTimeZone($timezone);
        $datetimePhp = new DateTimePhp('now', $timezonePhp);
        $datetimePhp->setTimestamp($timestamp);

        return $this->fromDateTimePhp($datetimePhp, $timezone);
    }

    public function fromFormatDateTime($dateTimeString, $sourceTimezone = self::TIMEZONE_GMT)
    {
        return $this->fromFormat(self::FORMAT_DATETIME, $dateTimeString, $sourceTimezone);
    }

    public function fromFormatDate($dateString, $sourceTimezone = self::TIMEZONE_GMT)
    {
        return $this->fromFormat(self::FORMAT_DATE_ONLY, $dateString, $sourceTimezone)->getDate();
    }

    public function fromFormat($sourceFormat, $dateTimeString, $sourceTimezone = self::TIMEZONE_GMT)
    {
        $sourceTimezone = new DateTimeZone($sourceTimezone);
        $dateTime = DateTimePhp::createFromFormat('!' . $sourceFormat, $dateTimeString, $sourceTimezone);

        if (!$dateTime) {
            throw new InvalidArgumentException($dateTimeString);
        }

        if ($dateTime->getTimezone()->getOffset($dateTime) !== $sourceTimezone->getOffset($dateTime)) {
            throw new InvalidArgumentException('dateTime timezone do NOT match sourceTimezone');
        }

        $dateTime->setTimezone(new DateTimeZone($this->appTimezone));

        return $this->fromDateTimePhp($dateTime, $this->appTimezone);
    }

    public function fromFormatWithTimezone($sourceFormat, $dateTimeString)
    {
        $dummyTimezoneName = 'Etc/Universal';
        $dateTime = DateTimePhp::createFromFormat('!' . $sourceFormat, $dateTimeString, new DateTimeZone($dummyTimezoneName));

        if (!$dateTime) {
            throw new InvalidArgumentException($dateTimeString);
        }

        if ($dateTime->getTimezone()->getName() === $dummyTimezoneName) {
            throw new \InvalidArgumentException('No timezone given in $dateTimeString');
        }

        $dateTime->setTimezone(new DateTimeZone($this->appTimezone));

        return $this->fromDateTimePhp($dateTime, $this->appTimezone);
    }

    private function fromDateTimePhp(DateTimePhp $dateTimePhp, $timezone)
    {
        return new DateTime(
            new Date(
                $dateTimePhp->format('Y'),
                $dateTimePhp->format('m'),
                $dateTimePhp->format('d')
            ),
            new Time(
                $dateTimePhp->format('H'),
                $dateTimePhp->format('i'),
                $dateTimePhp->format('s')
            ),
            $timezone
        );
    }
}
