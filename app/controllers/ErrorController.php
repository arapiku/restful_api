<?php

use Phalcon\Http\Response;

class ErrorController extends \Phalcon\Mvc\Controller
{

    public function notFoundAction()
    {
        $response = new Response();
        $response->setStatusCode('404', 'Not Found Page.');
        return $response;
    }
    
    public function uncaughtExceptionAction()
    {
        $response = new Response();
        $response->setStatusCode('500', 'Internal server error.');
        return $response;
    }

}

