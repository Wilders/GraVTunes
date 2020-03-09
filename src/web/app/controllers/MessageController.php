<?php


namespace app\controllers;

use app\helpers\Auth;
use app\models\Message;

use Slim\Http\Request;
use Slim\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MessageController extends Controller
{
    public function showMessages(Request $request, Response $response, array $args) : Response {
        $this->view->render($response, 'pages/contact.twig');
        return $response;
    }

    public function messages(Request $request, Response $response, array $args) : Response {
        try{
            //$ticket = filter_var($request->getParsedBodyParam('id'), FILTER_SANITIZE_NUMBER_INT);
            $messages = Message::where(['user_id' => Auth::user()->id])->get();

            $this->view->render($response, 'pages/contact.twig',[
                "messages" => $messages
            ]);
            return $response;

        }catch (ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
    }

}