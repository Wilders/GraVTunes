<?php

namespace app\controllers;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class HomeController
 * @author Jules Sayer <jules.sayer@protonmail.com>
 * @package mywishlist\controllers
 */
class HomeController extends Controller {

    /**
     * Appel index.twig
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function showHome(Request $request, Response $response, array $args): Response {
        $this->view->render($response, 'pages/index.twig');
        return $response;
    }
}