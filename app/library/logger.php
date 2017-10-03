// <?php

use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream as StreamAdapter;

class Logger
{
    private static final $plogger = new StreamAdapter("php://stderr");
    
    
    public static function error($args) {
        $plogger->log(Phalcon\Logger::ERROR, $args);
    }
    
    public static function info($args) {
        $plogger->log(Phalcon\Logger::ERROR, $args);
    }
    
}