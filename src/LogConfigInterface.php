<?php
/*
 *  Author: Aaron Sollman
 *  Email:  unclepong@gmail.com
 *  Date:   12/11/25
 *  Time:   20:01
*/


namespace Foamycastle\Analog;

use DateTimeZone;

interface LogConfigInterface
{
    function setFormat(string $format):self;
    function setDateTimeFormat(string $format):self;
    function setTimezone(?DateTimeZone $timezone=null):self;
}