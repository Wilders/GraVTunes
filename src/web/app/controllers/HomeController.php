<?php

namespace app\controllers;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class HomeController
 * @package app\controllers
 */
class HomeController extends Controller {

    public function showHome(Request $request, Response $response, array $args): Response {
        $this->view->render($response, 'pages/index.twig');
        return $response;
    }
}