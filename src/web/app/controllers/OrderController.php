<?php

namespace app\controllers;

use app\helpers\Auth;
use app\helpers\Basket;
use app\models\Commande;
use DateTime;
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

    public function createOrder(Request $request, Response $response, array $args): Response {
        try {
            if(Basket::count() === 0) throw new Exception("Votre panier est vide.");

            $order = new Commande();
            $order->user_id = Auth::user()->id;
            $order->total = Basket::subtotal() + 5;
            $order->paid = false;
            $order->statut = "créé";
            $order->creationDate = new DateTime();
            $order->save();

            $order->vinyles()->saveMany(Basket::all(), $this->getQuantities(Basket::all()));

            $this->flash->addMessage('success', "Votre commande a été créée!");
            $this->view->render($response, 'pages/cart.twig');
        } catch(Exception $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor("showCart"));
        }
        return $response;
    }

    private function getQuantities($items) {
        $quantities = [];

        foreach ($items as $item) {
            $quantities[] = ['quantite' => $item->quantity];
        }

        return $quantities;
    }
}