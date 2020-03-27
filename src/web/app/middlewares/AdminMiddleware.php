<?php

namespace app\middlewares;

use app\helpers\Auth;
use app\exceptions\AuthException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AdminMiddleware
 * @package app\middlewares
 */
class AdminMiddleware extends Middleware {

    /**
     * @param Request $request
     * @param Response $response
     * @param $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $next) {
        try {
            if (!Auth::check() && Auth::user()->role != 1) throw new AuthException();
        } catch(AuthException $e) {
            $this->container->flash->addMessage('error', 'Vous n\'être pas autorisé à accéder à cette page.');
            return $response->withRedirect($this->container->router->pathFor('showHome'));
        }

        $response = $next($request, $response);
        return $response;
    }
}