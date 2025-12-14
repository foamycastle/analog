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


    public function __construct(
        private string            $format,
        private array             $params = []
    )
    {
        parent::__construct($format, $params);
    }

}