<?php

namespace app\controllers;

use app\helpers\Auth;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class HomeController
 * @package app\controllers
 */
class HomeController extends Controller {

    public function showHome(Request $request, Response $response, array $args): Response {
        if(Auth::check()) {
            $response = $response->withRedirect($this->router->pathFor('appHome'));
        } else {
            $response = $response->withRedirect($this->router->pathFor('login'));
        }
        return $response;
    }
}