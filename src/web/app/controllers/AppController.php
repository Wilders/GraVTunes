<?php

namespace app\controllers;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AppController
 * @package app\controllers
 */
class AppController extends Controller {

    public function showHome(Request $request, Response $response, array $args): Response {
        $this->view->render($response, 'pages/home.twig');
        return $response;
    }

    public function btToken(Request $request, Response $response, array $args): Response {
        return $response->withJson([
            'token' => Braintree_ClientToken::generate()
        ]);
    }
}