<?php

namespace app\controllers;

use app\helpers\Basket;
use Exception;
use Slim\Http\Request;
use Slim\Http\Response;

class OrderController extends Controller {

    public function showOrder(Request $request, Response $response, array $args): Response {
        try {
            if(Basket::count() === 0) throw new Exception("Votre panier est vide.");

            $this->view->render($response, 'pages/order.twig');
        } catch(Exception $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor("showCart"));
        }
        return $response;
    }
}