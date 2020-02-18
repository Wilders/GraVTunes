<?php

namespace app\controllers;

use app\helpers\Auth;
use app\helpers\Basket;
use app\models\Commande;
use Braintree_Transaction;
use DateTime;
use Exception;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class OrderController
 * @package app\controllers
 */
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
            if(is_null($request->getParsedBodyParam('payment_method_nonce'))) throw new Exception("Une erreur est survenue lors du paiement. Vous n'avez pas été débité.");

            $order = new Commande();
            $order->user_id = Auth::user()->id;
            $order->total = Basket::subtotal() + 5;
            $order->paid = false;
            $order->statut = "créé";
            $order->creationDate = new DateTime();
            $order->save();

            $order->vinyles()->saveMany(Basket::all(), $this->getQuantities(Basket::all()));

            $result = Braintree_Transaction::sale([
                'amount' => Basket::subtotal() + 5,
                'paymentMethodNonce' => $request->getParsedBodyParam('payment_method_nonce'),
                'options' => [
                    'submitForSettlement' => true
                ]
            ]);

            if($result->success) {
                $order->update([
                    'paid' => true
                ]);
                Basket::clear();
                $order->paiement()->create([
                    'success' => true,
                    'transaction_id' => $result->transaction->id
                ]);
            } else {
                $order->paiement()->create([
                    'success' => false
                ]);
                throw new Exception("Votre paiement a été refusé.");
            }

            $this->flash->addMessage('success', "Votre paiement a été accepté et votre commande a été créée.");
            $response = $response->withRedirect($this->router->pathFor("appHome"));
        } catch(Exception $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor("showOrder"));
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