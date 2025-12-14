<?php
/*
 *  Author: Aaron Sollman
 *  Email:  unclepong@gmail.com
 *  Date:   12/11/25
 *  Time:   20:07
*/


namespace Foamycastle\Analog;

use DateTime;

class ParamMatcher implements \Stringable
{
    private array $resolvedParams=[];
    private array $matchedRawParams=[];
    private array $params=[];
    private string $compiledFormat='';
    public function __construct(
        private  string  $message,
        array   $context=[],
    )
    {
        $this->params=$context;
        $this->matchParams();
        $this->resolveParams();
    }
    public function __toString(): string
    {
        $this->compileEntry();
        return $this->compiledFormat;
    }
    protected function compileEntry():void
    {
        $this->compiledFormat = str_replace(array_keys($this->resolvedParams), array_values($this->resolvedParams), $this->message);
    }

    public function setFormat(string $message):self
    {
        $this->message=$message;
        return $this;
    }
    public function setParams(array $params):self
    {
        $this->params=$params;
        return $this;
    }

    protected function matchParams():void
    {
        preg_match_all('/%(\w+)%/', $this->message, $matches, PREG_PATTERN_ORDER);
        $this->matchedRawParams=array_combine($matches[1], $matches[0]);
    }
    protected function resolveParams():void
    {
        foreach ($this->params as $param=>$value) {
            $outputValue='';
            if(is_callable($value)){
                $outputValue=$value();
            }elseif($value instanceof \Closure){
                $outputValue=$value(...);
            }else{
                $outputValue=$value;
            }
            $this->resolvedParams[$this->matchedRawParams[$param] ?? $param]=$outputValue;

        }
    }
}