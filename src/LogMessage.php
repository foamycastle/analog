<?php
/*
 *  Author: Aaron Sollman
 *  Email:  unclepong@gmail.com
 *  Date:   12/13/25
 *  Time:   22:57
*/


namespace Foamycastle\Analog;

class LogMessage extends ParamMatcher
{
    public function __construct(string $message, array $context = [])
    {
        parent::__construct($message,$context);
    }
}