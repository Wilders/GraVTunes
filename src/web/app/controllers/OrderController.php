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

    public function order(Request $request, Response $response, array $args): Response {
        $order = Commande::where(["id" => $args['id'], "user_id" => Auth::user()->id])->firstOrFail();

        $this->view->render($response, 'pages/order.twig', [
            "order" => $order
        ]);
        return $response;
    }

    public function orders(Request $request, Response $response, array $args): Response {
        $orders = Auth::user()->commandes;

        $this->view->render($response, 'pages/orders.twig', [
            "orders" => $orders
        ]);
        return $response;
    }

    public function showAddOrder(Request $request, Response $response, array $args): Response {
        try {
            if(Basket::count() === 0) throw new Exception("Votre panier est vide.");

            $this->view->render($response, 'pages/addOrder.twig');
        } catch(Exception $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor("showCart"));
        }
        return $response;
    }

    public function addOrder(Request $request, Response $response, array $args): Response {
        try {
            if(Basket::count() === 0) throw new Exception("Votre panier est vide.");
            if(is_null($request->getParsedBodyParam('payment_method_nonce'))) throw new Exception("Une erreur est survenue lors du paiement. Vous n'avez pas été débité.");

            $order = new Commande();
            $order->user_id = Auth::user()->id;
            $order->total = Basket::subtotal() + 5;
            $order->statut = 1;
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
                Basket::clear();
                $order->paiement()->create([
                    'success' => true,
                    'transaction_id' => $result->transaction->id
                ]);
                $order->vinyles()->update([
                    "locked" => true
                ]);
            } else {
                $order->vinyles()->detach();
                $order->delete();
                throw new Exception("Votre paiement a été refusé. Vous n'avez pas été débité.");
            }

            $this->flash->addMessage('success', "Votre paiement a été accepté et votre commande a été créée.");
            $response = $response->withRedirect($this->router->pathFor("showOrder", ['id' => $order->id]));
        } catch(Exception $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor("showAddOrder"));
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