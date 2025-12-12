<?php
/*
 *  Author: Aaron Sollman
 *  Email:  unclepong@gmail.com
 *  Date:   12/11/25
 *  Time:   19:55
*/


namespace Foamycastle\Analog;

use DateTimeZone;

abstract class Analog implements LoggerInterface, LogConfigInterface
{
    /**
     * @var array<string,self>
     */
    private static array $instances = [];
    private string $name;
    private string $format = '%datetime% [%level%] %message%';
    /**
     * The instance of the logger accessed when a logging method is called statically.
     */
    private static self $instance;
    private string $dateTimeFormat;
    private ?DateTimeZone $timezone=null;

    public static function __callStatic(string $name, array $arguments):?static
    {
        if(!isset(self::$instances[$name])){
            return null;
        }
        return self::$instances[$name];
    }

    public static function configure(Analog $instance):LogConfigInterface
    {
        return $instance;
    }

    public static function setInstance(Analog $instance):LoggerInterface
    {
        self::$instance = $instance;
        return $instance;
    }

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
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function setFormat(string $format): LogConfigInterface
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    protected function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param DateTimeZone|null $timezone
     */
    public function setTimezone(?DateTimeZone $timezone=null): self
    {
        $this->timezone = $timezone ?? new DateTimeZone('UTC');
        return $this;
    }

    /**
     * @return DateTimeZone|null
     */
    public function getTimezone(): ?DateTimeZone
    {
        return $this->timezone;
    }
    public function setDateTimeFormat(string $format): LogConfigInterface
    {
        $this->dateTimeFormat = $format;
        return $this;
    }
    public function getDateTimeFormat(): string
    {
        return $this->dateTimeFormat;
    }
    public function getNow(?DateTimeZone $timezone=null):\DateTime
    {
        return new \DateTime(
            'now',
                $timezone
                ?? $this->getTimezone()
                ?? new DateTimeZone('UTC')
        );
    }

    abstract function log(LogLevel $level, $message, array $context = array());
}