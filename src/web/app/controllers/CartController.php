<?php

namespace app\controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class CartController extends Controller {

    public function showCart(Request $request, Response $response, array $args): Response {
        $this->view->render($response, 'pages/cart.twig');
        return $response;
    }



}