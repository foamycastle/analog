<?php
/*
 *  Author: Aaron Sollman
 *  Email:  unclepong@gmail.com
 *  Date:   12/11/25
 *  Time:   21:16
*/


namespace Foamycastle\Analog;

use DateTime;
use Foamycastle\Analog\Analog;
use Foamycastle\Analog\Entry\LogEntry;

class AnalogFile extends Analog
{
    protected string $path;
    protected $resource;
    public function __construct()
    {
        try{
            $path = env('ANALOG_PATH', 'analog.log');
        }catch (\Exception $e){
            try {
                defined('ANALOG_PATH') && $path = constant('ANALOG_PATH');
                defined('LOG_PATH') && $path ??= constant('LOG_PATH');
            }catch(\Exception $e){
                $path = 'analog.log';
            }
        }
        if(!file_exists($path)){
            if(!touch($path)){
                throw new \Exception('Unable to create file '.$path);
            }
        }
        if(!realpath($path)) {
            if(dirname($path) === '.'){
                $this->path =  __DIR__.DIRECTORY_SEPARATOR.$path;
            }else {
                $this->path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename($path);
            }
        }else{
            $this->path = $path;
        }
        $this->resource = @fopen($this->path, 'a+') ?: fopen('php://temp', 'a+');
        if(!$this->resource) {
            throw new \Exception('Unable to open file '.$this->path);
        }
        $this->loggerState=true;
    }
    public function __destruct()
    {
        fclose($this->resource);
    }

    /**
     * @param LogLevel $level
     * @param string $message
     * @param array $context
     * @throws \DateInvalidTimeZoneException
     */
    function log($level, $message, array $context = array())
    {
        /** @var LogLevel $level */
        if(!$this->loggerState) return;
        $timezone = env("ANALOG_TIMEZONE",date_default_timezone_get());
        try {
            $datetime = (new \DateTime('now', new \DateTimeZone($timezone)))->format('Y-m-d H:i:s');
        }catch (\Exception $e){
            $timezone = 'UTC';
            $datetime = (new \DateTime('now', new \DateTimeZone($timezone)))->format('Y-m-d H:i:s');
        }
        $format = env('ANALOG_FORMAT', '[%datetime%] %timezone% [%level%]: %message%');

        $entry = new LogEntry($format, [
            'level' => $level->value,
            'message' => (new LogMessage($message, $context)),
            'datetime' => $datetime,
            'timezone' => $timezone
        ]);

        fwrite($this->resource, $entry.PHP_EOL);
    }
}