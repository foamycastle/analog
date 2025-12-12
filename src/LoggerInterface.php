<?php
/*
 *  Author: Aaron Sollman
 *  Email:  unclepong@gmail.com
 *  Date:   12/11/25
 *  Time:   20:01
*/


namespace Foamycastle\Analog;

interface LoggerInterface
{
    function emergency($message, array $context = array());
    function alert($message, array $context = array());
    function critical($message, array $context = array());
    function error($message, array $context = array());
    function warning($message, array $context = array());
    function notice($message, array $context = array());
    function info($message, array $context = array());
    function debug($message, array $context = array());
}