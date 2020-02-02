<?php

namespace app\middlewares;

use app\helpers\Auth;
use app\exceptions\AuthException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class OldInputMiddleware
 * @author Jules Sayer <jules.sayer@protonmail.com>
 * @package app\middlewares
 */
class AuthMiddleware extends Middleware {

    /**
     * @param Request $request
     * @param Response $response
     * @param $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $next) {
        try {
            if (!Auth::check()) throw new AuthException();
        } catch(AuthException $e) {
            $this->container->flash->addMessage('error', 'Vous devez être connecté pour effectuer cette action.');
            return $response->withRedirect($this->container->router->pathFor('login'));
        }

        $response = $next($request, $response);
        return $response;
    }
}