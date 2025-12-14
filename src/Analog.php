<?php
/*
 *  Author: Aaron Sollman
 *  Email:  unclepong@gmail.com
 *  Date:   12/11/25
 *  Time:   19:55
*/


namespace Foamycastle\Analog;

use DateTimeImmutable;
use DateTimeZone;

abstract class Analog implements LoggerInterface
{
    protected bool $loggerState = false;

    function emergency($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    function alert($message, array $context = array())
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    function error($message, array $context = array())
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG,$message, $context);
    }

    abstract protected function log($level, $message, array $context = array());
}