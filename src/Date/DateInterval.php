<?php

namespace Kutny\DateTimeBundle\Date;

class DateInterval
{
    private $from;
    private $to;

    public function __construct(Date $from, Date $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function getLengthInDays()
    {
        $startPhpDateTime = $this->from->toDateTime();
        $endPhpDateTime = $this->to->toDateTime();
        $phpDateTimeDiff = $startPhpDateTime->diff($endPhpDateTime, true);

        return $phpDateTimeDiff->days;
    }
}
