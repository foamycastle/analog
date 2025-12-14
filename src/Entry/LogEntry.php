<?php
/*
 *  Author: Aaron Sollman
 *  Email:  unclepong@gmail.com
 *  Date:   12/13/25
 *  Time:   22:59
*/


namespace Foamycastle\Analog\Entry;

use DateTimeImmutable;
use DateTimeZone;
use Foamycastle\Analog\LogLevel;
use Foamycastle\Analog\LogMessage;
use Foamycastle\Analog\ParamMatcher;

class LogEntry extends ParamMatcher
{

    private DateTimeImmutable $datetime;
    private DateTimeZone $timezone;

    public function __construct(
        private string            $format,
        private array             $params = []
    )
    {
        $this->timezone = new DateTimeZone(env('ANALOG_TIMEZONE', date_default_timezone_get()));
        $this->datetime = new DateTimeImmutable('now', $this->timezone);
        parent::__construct($format, $params);

    }

}