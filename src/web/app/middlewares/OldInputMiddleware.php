<?php

namespace app\middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class OldInputMiddleware
 * @package app\middlewares
 */
class OldInputMiddleware extends Middleware {

    /**
     * @param Request $request
     * @param Response $response
     * @param $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $next) {
        if (isset($_SESSION['oldData'])) {
            $this->container->view->getEnvironment()->addGlobal('oldData', $_SESSION['oldData']);
        }
        $_SESSION['oldData'] = $request->getParams();

        $response = $next($request, $response);
        return $response;
    }
}