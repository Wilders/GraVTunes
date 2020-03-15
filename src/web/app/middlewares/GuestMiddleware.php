<?php

namespace app\middlewares;

use app\exceptions\AuthException;
use app\helpers\Auth;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class GuestMiddleware
 * @package app\middlewares
 */
class GuestMiddleware extends Middleware {

    /**
     * @param Request $request
     * @param Response $response
     * @param $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $next) {
        try {
            if (Auth::check()) throw new AuthException();
        } catch(AuthException $e) {
            $this->container->flash->addMessage('error', 'Vous ne pouvez pas effectuer cette action en étant connecté.');
            return $response->withRedirect($this->container->router->pathFor('showHome'));
        }

        $response = $next($request, $response);
        return $response;
    }
}