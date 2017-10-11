<?php

use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream as StreamAdapter;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Logger\Formatter\Line as LineFormatter;

class Plogger
{
    
    public static function error($message) {
        var_dump("[ERROR] " . $message);
    }
    
    public static function info($message) {
        var_dump("[INFO] " . $message);
    }
    
    public static function debug($message) {
        var_dump("[DEBUG] " . $message);
    }
    
}