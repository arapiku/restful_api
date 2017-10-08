<?php

namespace App\Library\Log;

use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream as StreamAdapter;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Logger\Formatter\Line as LineFormatter;

class Plogger
{
    
    public $message;
    
    public function __construct($message) {
        $this->message = $message;
    }
    
    public static function error() {
        $plogger = new StreamAdapter("php://stdout");
        $plogger->error($this->message);
    }
    
    public static function info() {
        $plogger = new StreamAdapter("php://stdout");
        $plogger->info($this->message);
    }
    
    public function debug() {
        $plogger = new StreamAdapter("php://stdout");
        $plogger->setLogLevel(Logger::DEBUG);
        $plogger->debug($this->message);
    }
    
}