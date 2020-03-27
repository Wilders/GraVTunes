<?php

namespace app\controllers;

use app\models\Commande;
use app\models\Ticket;
use app\models\Track;
use app\models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AdminController
 * @package app\controllers
 */
class AdminController extends Controller {

    public function showHome(Request $request, Response $response, array $args): Response {
        $files = [];
        foreach (Track::all() as $track) {
            $files[] = $track->file->size;
        }

        $this->view->render($response, 'pages/admin/home.twig', [
            "users" => User::all(),
            "tracks" => Track::all(),
            "orders" => Commande::all(),
            "tickets" => Ticket::all(),
            "arroundDiskSpace" => round(array_sum($files)/(1024*1024),2)
        ]);
        return $response;
    }

    public function nextStepOrder(Request $request, Response $response, array $args): Response {
        try {
            $order = Commande::where('id', $args['id'])->firstOrFail();
            if($order->statut < 5) {
                $order->statut += 1;
            }
            $order->save();

            $this->flash->addMessage('success', "Vous avez fait évoluer la commande #$order->id.");
            $response = $response->withRedirect($this->router->pathFor("showAdminHome"));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de faire évoluer cette commande.");
            $response = $response->withRedirect($this->router->pathFor("showAdminHome"));
        }
        return $response;
    }

    public function showTicket(Request $request, Response $response, array $args): Response {
        try {
            $ticket = Ticket::where('id', $args['id'])->firstOrFail();
            $messages = $ticket->messages;

            $this->view->render($response, 'pages/ticket.twig',[
                "messages" => $messages,
                "ticket" => $ticket
            ]);
        } catch (ModelNotFoundException $e){
            $this->flash->addMessage('error', "Impossible de trouver ce ticket.");
            $response = $response->withRedirect($this->router->pathFor("showAdminHome"));
        }
        return $response;
    }

    public function showOrder(Request $request, Response $response, array $args): Response {
        try {
            $order = Commande::where(["id" => $args['id']])->firstOrFail();

            $this->view->render($response, 'pages/order.twig', [
                "order" => $order
            ]);
            return $response;
        } catch (ModelNotFoundException $e){
            $this->flash->addMessage('error', "Impossible de trouver cette commande.");
            $response = $response->withRedirect($this->router->pathFor("showAdminHome"));
        }
        return $response;
    }

    public function closeTicket(Request $request, Response $response, array $args): Response {
        try {
            $ticket = Ticket::where('id', $args['id'])->firstOrFail();

            $ticket->statut = 1;
            $ticket->save();

            $this->flash->addMessage('success', "Vous avez clos le ticket #$ticket->id.");
            $response = $response->withRedirect($this->router->pathFor("showAdminHome"));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de clore ce ticket.");
            $response = $response->withRedirect($this->router->pathFor("showAdminHome"));
        }
        return $response;
    }

}