<?php

namespace app\controllers;

use app\helpers\Auth;
use app\helpers\Basket;
use app\models\Vinyle;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class CartController extends Controller {

    public function showCart(Request $request, Response $response, array $args): Response {
        $this->view->render($response, 'pages/cart.twig');
        return $response;
    }

    public function addCart(Request $request, Response $response, array $args): Response {
        try {
            $item_id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);
            $quantity = isset($args['quantity']) ? filter_var($args['quantity'], FILTER_SANITIZE_NUMBER_INT) : 1;

            $vinyle = Vinyle::where(['id' => $item_id, 'user_id' => Auth::user()->id])->firstOrFail();

            Basket::add($vinyle, $quantity);
            var_dump($_SESSION['cart']);
            die('added');

        } catch(ModelNotFoundException $e) {
            $this->container->flash->addMessage('error', 'Impossible d\'ajouter ce vinyle.');
            $response = $response->withRedirect($this->router->pathFor('appHome'));
        }
        return $response;
    }

}