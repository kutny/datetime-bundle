<?php

namespace Kutny\DateTimeBundle\Date;

use DateTime as DateTimePhp;
use DateInterval as DateIntervalPhp;

class Date
{
    const DAY_MONDAY = 1;
    const DAY_TUESDAY = 2;
    const DAY_WEDNESDAY = 3;
    const DAY_THURSDAY = 4;
    const DAY_FRIDAY = 5;
    const DAY_SATURDAY = 6;
    const DAY_SUNDAY = 7;

    private $year;
    private $month;
    private $day;

    public function __construct($year, $month, $day)
    {
        $this->year = (int) $year;
        $this->month = (int) $month;
        $this->day = (int) $day;
    }

    public function toTimestamp()
    {
        return mktime(0, 0, 0, $this->month, $this->day, $this->year);
    }

    public function toFormat($format)
    {
        return date($format, $this->toTimestamp());
    }

    public function getDay()
    {
        return $this->day;
    }

    public function getDayOfWeek()
    {
        return (int) date('N', $this->toTimestamp());
    }

    public function getWeek()
    {
        return (int) date('W', $this->toTimestamp());
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function isSameAs(Date $anotherDate)
    {
        return ($this->day === $anotherDate->getDay() && $this->month === $anotherDate->getMonth() && $this->year === $anotherDate->getYear());
    }

    public function toDateTime(): DateTimePhp
    {
        return new DateTimePhp($this->toFormat('r'));
    }

    public function addDays($days)
    {
        return $this->addIntervalBySpec('P' . $days . 'D');
    }

    public function addMonths($months)
    {
        return $this->addIntervalBySpec('P' . $months . 'M');
    }

    public function addYears($years)
    {
        return $this->addIntervalBySpec('P' . $years . 'M');
    }

    public function subDays($days)
    {
        return $this->subIntervalBySpec('P' . $days . 'D');
    }

    public function subMonths($months)
    {
        return $this->subIntervalBySpec('P' . $months . 'M');
    }

    public function subYears($years)
    {
        return $this->subIntervalBySpec('P' . $years . 'Y');
    }

    public function getDaysFrom(Date $date)
    {
        $startPhpDateTime = $date->toDateTime();
        $endPhpDateTime = $this->toDateTime();
        $phpDateTimeDiff = $startPhpDateTime->diff($endPhpDateTime, true);
        $startTimezoneOffset = $startPhpDateTime->getTimezone()->getOffset($startPhpDateTime);
        $endTimezoneOffset = $endPhpDateTime->getTimezone()->getOffset($startPhpDateTime);

        if ($endTimezoneOffset > $startTimezoneOffset) {
            $days = $phpDateTimeDiff->days + 1;
        }
        else {
            $days = $phpDateTimeDiff->days;
        }

        if ($endPhpDateTime->getTimestamp() >= $startPhpDateTime->getTimestamp()) {
            return $days;
        }
        else {
            return -$days;
        }
    }

    private function addIntervalBySpec($intervalSpec)
    {
        $thisDateTime = $this->toDateTime();
        $thisDateTime->add(new DateIntervalPhp($intervalSpec));

        return $this->fromDateTimePhp($thisDateTime);
    }

    private function subIntervalBySpec($intervalSpec)
    {
        $thisDateTime = $this->toDateTime();
        $thisDateTime->sub(new DateIntervalPhp($intervalSpec));

        return $this->fromDateTimePhp($thisDateTime);
    }

    private function fromDateTimePhp(DateTimePhp $dateTimePhp)
    {
        return new Date(
            $dateTimePhp->format('Y'),
            $dateTimePhp->format('m'),
            $dateTimePhp->format('d')
        );
    }
}
