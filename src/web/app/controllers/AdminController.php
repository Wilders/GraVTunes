<?php


namespace app\controllers;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AdminController
 * @package app\controllers
 */
class AdminController extends Controller {

    public function showHome(Request $request, Response $response, array $args): Response {
        $this->view->render($response, 'pages/admin/home.twig');
        return $response;
    }
}