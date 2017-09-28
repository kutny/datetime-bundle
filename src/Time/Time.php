<?php

namespace Kutny\DateTimeBundle\Time;

class Time
{
    private $hour;
    private $minute;
    private $second;

    public function __construct($hour, $minute, $second)
    {
        $this->hour = (int) $hour;
        $this->minute = (int) $minute;
        $this->second = (int) $second;
    }

    public function getHour()
    {
        return $this->hour;
    }

    public function getMinute()
    {
        return $this->minute;
    }

    public function getSecond()
    {
        return $this->second;
    }

    public function toFormat($format)
    {
        return date(
            $format,
            mktime(
                $this->hour,
                $this->minute,
                $this->second
            )
        );
    }

    public function isSameAs(Time $anotherTime)
    {
        return ($this->second === $anotherTime->getSecond() && $this->minute === $anotherTime->getMinute() && $this->hour === $anotherTime->getHour());
    }
}
