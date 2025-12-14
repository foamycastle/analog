<?php
/*
 *  Author: Aaron Sollman
 *  Email:  unclepong@gmail.com
 *  Date:   12/11/25
 *  Time:   21:16
*/


namespace Foamycastle\Analog;

use DateTime;
use DateTimeImmutable;
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

        $this->entrySetup();
    }
    public function __destruct()
    {
        fclose($this->resource);
    }

    protected function entrySetup()
    {
        $this->setFormat("[%datetime%] %timezone% [%level%] %message%");
        $this->setDateTimeFormat('Y-m-d H:i:s');
        $this->setTimezone(new \DateTimeZone(env('ANALOG_TIMEZONE','UTC')));
        $this->setDateTime(new DateTimeImmutable('now', $this->timezone));
        $this->setParams([
            'datetime' => $this->now->setTimestamp(time())->setTimezone($this->timezone)->format($this->dateTimeFormat),
            'timezone' => $this->timezone->getName()
        ]);

    }


}