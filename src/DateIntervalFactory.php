<?php

namespace Kutny\DateTimeBundle;

use Kutny\DateTimeBundle\Date\DateInterval;

class DateIntervalFactory
{
    private $dateTimeFactory;

    public function __construct(
        DateTimeFactory $dateTimeFactory
    ) {
        $this->dateTimeFactory = $dateTimeFactory;
    }
    
    public function create($from, $to)
    {
        return new DateInterval(
            $this->dateTimeFactory->fromFormatDate($from),
            $this->dateTimeFactory->fromFormatDate($to)
        );
    }
}
