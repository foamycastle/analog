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
use Foamycastle\Analog\Entry\LogEntry;

abstract class Analog implements LoggerInterface, LogConfigInterface
{
    protected bool $loggerState = false;
    protected string $format = '';
    protected string $dateTimeFormat = '';
    protected ?DateTimeZone $timezone = null;
    protected array $params = [];
    protected DateTimeImmutable $now;

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

    function log($level, $message, array $context = array())
    {
        /** @var LogLevel $level */

        $entry = new LogEntry($this->format, [
            'level'=>$level->value,
            'message'=>new LogMessage($message,$context),
            ...$this->params
        ]);

        fwrite($this->resource, $entry.PHP_EOL);
    }

    abstract protected function entrySetup();

    function setFormat(?string $format=null): LogConfigInterface
    {
        $this->format = $format ?? env("ANALOG_FORMAT","[%datetime%] %timezone% [%level%] %message%");
        return $this;
    }

    function setDateTimeFormat(?string $format=null): LogConfigInterface
    {
        $this->dateTimeFormat = $format ?? "Y-m-d H:i:s";
        return $this;
    }

    public function setDateTime(?\DateTimeImmutable $dateTime=null): LogConfigInterface
    {
        $this->now = $dateTime ?? new DateTimeImmutable('now', $this->timezone ?? new DateTimeZone('UTC'));
        return $this;
    }

    function setTimezone(?DateTimeZone $timezone = null): LogConfigInterface
    {
        $this->timezone = $timezone ?? new DateTimeZone(env('ANALOG_TIMEZONE', 'UTC'));
        return $this;
    }

    function setParams(array $params=[]): LogConfigInterface
    {
        $this->params = array_merge(($this->params ?? []),$params);
        return $this;
    }

    function configure():LogConfigInterface
    {
        return $this;
    }


}