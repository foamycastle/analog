<?php
/*
 *  Author: Aaron Sollman
 *  Email:  unclepong@gmail.com
 *  Date:   12/11/25
 *  Time:   20:07
*/


namespace Foamycastle\Analog;

use DateTime;

class LogMessage implements \Stringable
{
    private array $resolvedParams=[];
    private array $matchedRawParams=[];
    private string $compiledFormat='';
    public function __construct(
        private readonly string  $message,
        private readonly array   $context=[],
    )
    {
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
        $this->compiledFormat = str_replace($this->matchedRawParams, $this->resolvedParams, $this->message);
    }

    public function setMessage(string $message):self
    {
        return new self(
            $message,
            $this->context,

        );
    }
    public function setParams(array $params):self
    {
        return new self(
            $this->message,
            $params,
        );
    }

    protected function matchParams():void
    {
        preg_match_all('/%(\w+)%/', $this->message, $matches, PREG_SET_ORDER);
        $this->matchedRawParams=array_column($matches, 0);
    }
    protected function resolveParams():void
    {
        foreach ($this->context as $key=>$item) {
            if(is_callable($item))
                $this->resolvedParams[$key]=$item();
            elseif($item instanceof \Closure)
                $this->resolvedParams[$key]=$item(...);
            else
                $this->resolvedParams[$key]=$item;
        }
    }
}