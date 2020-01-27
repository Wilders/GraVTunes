<?php

namespace app\middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class OldInputMiddleware extends Middleware {

    public function __invoke(Request $request, Response $response, $next){
        $this->container->view->getEnvironment()->addGlobal('oldData', $_SESSION['oldData']);
        $_SESSION['oldData'] = $request->getParams();

        $response = $next($request, $response);
        return $response;
    }
}