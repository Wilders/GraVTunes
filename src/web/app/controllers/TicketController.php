<?php


namespace app\controllers;


use app\helpers\Auth;
use app\models\Ticket;

use Slim\Http\Request;
use Slim\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends Controller
{
    public function showTickets(Request $request, Response $response, array $args) : Response {
        $this->view->render($response, 'pages/tickets.twig');
        return $response;
    }

    public function newTicket(Request $request, Response $response, array $args) : Response {
        $this->view->render($response, 'pages/newTicket.twig');
        return $response;
    }

    public function tickets(Request $request, Response $response, array $args) : Response {
        try{
            $tickets = Ticket::where(['user_id' => Auth::user()->id, 'statut' => 0])->get();

            $this->view->render($response, 'pages/tickets.twig',[
                "tickets" => $tickets
            ]);
            return $response;

        }catch (ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
    }

    public function createTicket(Request $request, Response $response, array $args) : Response {
        try{
            $objet = filter_var($request->getParsedBodyParam('title'), FILTER_SANITIZE_SPECIAL_CHARS);

            $ticket = new Ticket();


            $ticket->objet = $objet;
            $ticket->user_id = Auth::user()->id;
            $ticket->creationDate;

            $ticket->save();

            $this->flash->addMessage('success',"Nouveau ticket créé.");
            $response = $response->withRedirect($this->router->pathFor("appTickets"));
        }catch(ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }

    public function closeTicket(Request $request, Response $response, array $args) : Response {
        try{
            $id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

            $ticket = Ticket::where(["id" => $id, "user_id" => Auth::user()->id])->firstOrFail();
            $ticket->statut=1;

            $ticket->save();

            $this->flash->addMessage('success',"Vous avez clos votre ticket.");
            $response = $response->withRedirect($this->router->pathFor("appTickets"));
        }catch (ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }
}