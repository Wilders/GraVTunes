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

            $this->flash->addMessage('success',"Votre vinyle a été ajouté au panier.");
            $response = $response->withRedirect($this->router->pathFor("showCart"));
        } catch(ModelNotFoundException $e) {
            $this->container->flash->addMessage('error', 'Impossible d\'ajouter ce vinyle.');
            $response = $response->withRedirect($this->router->pathFor('appHome'));
        }
        return $response;
    }

    public function updateCart(Request $request, Response $response, array $args): Response {
        try {
            $item_id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);
            $quantity = filter_var($request->getParsedBodyParam('quantity'), FILTER_SANITIZE_NUMBER_INT);

            $vinyle = Vinyle::where(['id' => $item_id, 'user_id' => Auth::user()->id])->firstOrFail();

            Basket::update($vinyle, $quantity);

            $this->container->flash->addMessage('success', "Votre panier à été mis à jour!");
            $response = $response->withRedirect($this->router->pathFor('showCart'));
        } catch(ModelNotFoundException $e) {
            $this->container->flash->addMessage('error', 'Impossible de modifier la quantité de ce vinyle.');
            $response = $response->withRedirect($this->router->pathFor('appHome'));
        }
        return $response;
    }

    public function clearCart(Request $request, Response $response, array $args): Response {
        Basket::clear();

        $this->container->flash->addMessage('success', "Votre panier à été vidé!");
        $response = $response->withRedirect($this->router->pathFor('showCart'));
        return $response;
    }
}