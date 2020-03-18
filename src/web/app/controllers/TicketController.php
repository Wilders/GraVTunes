<?php

namespace app\controllers;

use app\helpers\Auth;
use app\models\Ticket;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class TicketController
 * @package app\controllers
 */
class TicketController extends Controller {

    public function ticket(Request $request, Response $response, array $args): Response {
        try {
            $ticket = Auth::user()->tickets()->where('id', $args['id'])->firstOrFail();
            $messages = $ticket->messages;

            $this->view->render($response, 'pages/ticket.twig',[
                "messages" => $messages,
                "ticket" => $ticket
            ]);
        } catch (ModelNotFoundException $e){
            $this->flash->addMessage('error', "Impossible de trouver ce ticket.");
            $response = $response->withRedirect($this->router->pathFor("showTickets"));
        }
        return $response;
    }

    public function tickets(Request $request, Response $response, array $args): Response {
        $tickets = Ticket::where(['user_id' => Auth::user()->id, 'statut' => 0])->get();

        $this->view->render($response, 'pages/tickets.twig', [
            "tickets" => $tickets
        ]);
        return $response;
    }

    public function closedTickets(Request $request, Response $response, array $args): Response {
        $tickets = Ticket::where(['user_id' => Auth::user()->id, 'statut' => 1])->get();

        $this->view->render($response, 'pages/closedTickets.twig', [
            "tickets" => $tickets
        ]);
        return $response;
    }

    public function showAddTicket(Request $request, Response $response, array $args): Response {
        $this->view->render($response, 'pages/addTicket.twig');
        return $response;
    }

    public function addTicket(Request $request, Response $response, array $args): Response {
        $objet = filter_var($request->getParsedBodyParam('title'), FILTER_SANITIZE_STRING);

        $ticket = new Ticket();
        $ticket->objet = $objet;
        $ticket->user_id = Auth::user()->id;
        $ticket->save();

        $this->flash->addMessage('success', "Votre ticket a bien été créé.");
        $response = $response->withRedirect($this->router->pathFor("showTickets"));
        return $response;
    }

    public function closeTicket(Request $request, Response $response, array $args): Response {
        try {
            $ticket = Ticket::where(['id' => $args['id'], "user_id" => Auth::user()->id])->firstOrFail();

            $ticket->statut = 1;
            $ticket->save();

            $this->flash->addMessage('success', "Vous avez clos votre ticket.");
            $response = $response->withRedirect($this->router->pathFor("showTickets"));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de clore ce ticket.");
            $response = $response->withRedirect($this->router->pathFor("showTickets"));
        }
        return $response;
    }
}