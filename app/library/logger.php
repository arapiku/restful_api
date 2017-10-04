// <?php

use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream as StreamAdapter;
use Phalcon\Logger\Adapter\File as FileAdapter;

class Logger
{
    private static final $plogger = new StreamAdapter("php://stdout");
    
//     $plogger = new FileAdapter(
//         'app/logs/error.log',
//         [
//             'mode' => 'w',
//         ]
//     );
    
    public static function error($args) {
        $plogger->error($args);
    }
    
    public static function info($args) {
        $plogger->info($args);
    }
    
    public static function debug($args) {
        $plogger->debug($args);
    }
}