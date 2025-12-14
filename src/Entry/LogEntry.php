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
use Foamycastle\Analog\LogConfigInterface;
use Foamycastle\Analog\LogLevel;
use Foamycastle\Analog\LogMessage;
use Foamycastle\Analog\ParamMatcher;

class LogEntry extends ParamMatcher
{
    public function __construct(
        string  $format,
        array   $params = []
    )
    {
        parent::__construct($format, $params);
    }

}