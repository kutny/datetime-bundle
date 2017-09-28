<?php

namespace Kutny\DateTimeBundle;

use Kutny\DateTimeBundle\Date\Date;
use Kutny\DateTimeBundle\Time\Time;
use DateInterval;
use DateTime as DateTimePhp;
use DateTimeZone;

class DateTime
{
    private $date;
    private $time;
    private $timezone;

    public function __construct(Date $date, Time $time, $timezone = DateTimeFactory::TIMEZONE_GMT)
    {
        $this->date = $date;
        $this->time = $time;
        $this->timezone = $timezone;
    }

    public function toTimestamp()
    {
        return $this->createDateTimePhp()->getTimestamp();
    }

    public function getDate(): Date
    {
        return $this->date;
    }

    public function getTime(): Time
    {
        return $this->time;
    }

    public function isSameAs(DateTime $anotherDateTime)
    {
        return $this->date->isSameAs($anotherDateTime->getDate()) && $this->time->isSameAs($anotherDateTime->getTime());
    }

    public function toFormat($format)
    {
        return $this->createDateTimePhp()->format($format);
    }

    public function toDateTime()
    {
        return new DateTimePhp($this->toFormat('r'));
    }

    public function isBetween(DateTime $start, DateTime $end)
    {
        $thisDateTime = $this->toDateTime();
        return $thisDateTime < $end->toDateTime() ? $thisDateTime > $start->toDateTime() : false;
    }

    public function addMonths($months)
    {
        return $this->addIntervalBySpec('P' . $months . 'M');
    }

    public function addDays($days)
    {
        return $this->addIntervalBySpec('P' . $days . 'D');
    }

    public function addHours($hours)
    {
        return $this->addIntervalBySpec('PT' . $hours . 'H');
    }

    public function addMinutes($minutes)
    {
        return $this->addIntervalBySpec('PT' . $minutes . 'M');
    }

    public function addWorkingDays($days)
    {
        $weekendDays = ((int)($days / 5) * 2);

        $thisDateTime = $this->toDateTime();
        $thisDateTime->add(new DateInterval('P' . ($days + $weekendDays) . 'D'));

        if ($thisDateTime->format('N') < $this->toDateTime()->format('N')) {
            $thisDateTime->add(new DateInterval('P' . ((int)$this->toDateTime()->format('N') === 7 ? 1 : 2) . 'D'));
        }

        if ((int)$thisDateTime->format('N') === 7) {
            $thisDateTime->add(new DateInterval('P' . 1 . 'D'));
        }

        if ((int)$thisDateTime->format('N') === 6) {
            $thisDateTime->add(new DateInterval('P' . 2 . 'D'));
        }

        return $this->fromDateTimePhp($thisDateTime);
    }

    public function subMonths($months)
    {
        return $this->subIntervalBySpec('P' . $months . 'M');
    }

    public function subDays($days)
    {
        return $this->subIntervalBySpec('P' . $days . 'D');
    }

    public function subHours($hours)
    {
        return $this->subIntervalBySpec('PT' . $hours . 'H');
    }

    public function subMinutes($minutes)
    {
        return $this->subIntervalBySpec('PT' . $minutes . 'M');
    }

    private function subIntervalBySpec($intervalSpec)
    {
        $thisDateTime = $this->toDateTime();
        $thisDateTime->sub(new DateInterval($intervalSpec));

        return $this->fromDateTimePhp($thisDateTime);
    }

    private function addIntervalBySpec($intervalSpec)
    {
        $thisDateTime = $this->toDateTime();
        $thisDateTime->add(new DateInterval($intervalSpec));

        return $this->fromDateTimePhp($thisDateTime);
    }

    private function fromDateTimePhp(DateTimePhp $dateTimePhp)
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
            )
        );
    }

    private function createDateTimePhp()
    {
        $timezonePhp = new DateTimeZone($this->timezone);

        $datetimePhp = new DateTimePhp('now', $timezonePhp);
        $datetimePhp->setDate($this->date->getYear(), $this->date->getMonth(), $this->date->getDay());
        $datetimePhp->setTime($this->time->getHour(), $this->time->getMinute(), $this->time->getSecond());

        return $datetimePhp;
    }
}
