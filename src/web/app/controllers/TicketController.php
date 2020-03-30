<?php

namespace app\controllers;

use app\helpers\Auth;
use app\models\Ticket;
use Exception;
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

            $this->view->render($response, 'pages/ticket.twig', [
                "messages" => $messages,
                "ticket" => $ticket
            ]);
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de trouver ce ticket.");
            $response = $response->withRedirect($this->router->pathFor("showTickets"));
        }
        return $response;
    }

    public function tickets(Request $request, Response $response, array $args): Response {
        $tickets = Auth::user()->tickets()->where('statut', 0)->get();

        $this->view->render($response, 'pages/tickets.twig', [
            "tickets" => $tickets
        ]);
        return $response;
    }

    public function closedTickets(Request $request, Response $response, array $args): Response {
        $tickets = Auth::user()->tickets()->where('statut', 1)->get();

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
        $objet = filter_var($request->getParsedBodyParam('objet'), FILTER_SANITIZE_STRING);
        $message = filter_var($request->getParsedBodyParam('message'), FILTER_SANITIZE_STRING);

        $ticket = Auth::user()->tickets()->create([
            "objet" => $objet
        ]);

        $ticket->messages()->create([
            'message' => $message,
            'user_id' => Auth::user()->id
        ]);
        $ticket->touch();

        $this->flash->addMessage('success', "Votre ticket a bien été créé.");
        $response = $response->withRedirect($this->router->pathFor("showTickets"));
        return $response;
    }

    public function closeTicket(Request $request, Response $response, array $args): Response {
        try {
            $ticket = Auth::user()->tickets()->where('id', $args['id'])->firstOrFail();

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

    public function addMessage(Request $request, Response $response, array $args): Response {
        try {
            $contenu = filter_var($request->getParsedBodyParam('message'), FILTER_SANITIZE_STRING);
            if (Auth::user()->role == 1) {
                $ticket = Ticket::where('id', $args['id'])->firstOrFail();
            } else {
                $ticket = Auth::user()->tickets()->where('id', $args['id'])->firstOrFail();
            }
            if ($ticket->statut == 1) throw new Exception("Impossible d'ajouter un message à un ticket clos.");
            $ticket->messages()->create([
                'message' => $contenu,
                'user_id' => Auth::user()->id
            ]);
            $ticket->touch();

            $this->flash->addMessage('success', "Votre message a bien été envoyé.");
            if (Auth::user()->role == 1) {
                $response = $response->withRedirect($this->router->pathFor("adminShowTicket", ["id" => $args['id']]));
            } else {
                $response = $response->withRedirect($this->router->pathFor("showTicket", ["id" => $args['id']]));
            }
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible d'ajouter un message à ce ticket.");
            $response = $response->withRedirect($this->router->pathFor("showTickets"));
        } catch (Exception $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor("showClosedTickets"));
        }
        return $response;
    }
}