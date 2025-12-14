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

class AnalogFile extends Analog
{
    private bool $loggerState=false;
    private $resource;
    private string $path;
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
        Analog::configure($this)->setTimezone(new \DateTimeZone(env('ANALOG_TIMEZONE', 'UTC')));
    }
    public function __destruct()
    {
        fclose($this->resource);
    }

    function log(LogLevel $level, $message, array $context = array())
    {
        if(!$this->loggerState) return;
        $entry = new LogMessage(
            $this->getFormat(),
            $message,
            $level,
            $context,
            $this->getNow()
        );
        fputs($this->resource, $entry.PHP_EOL);
    }
}