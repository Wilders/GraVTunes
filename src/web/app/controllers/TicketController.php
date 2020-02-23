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

    public function tickets(Request $request, Response $response, array $args) : Response {
        try{
            $tickets = Ticket::where('user_id','=',Auth::user()->id)->get();

            $this->view->render($response, 'pages/tickets.twig',[
                "tickets" => $tickets
            ]);
            return $response;

        }catch (ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
    }
}