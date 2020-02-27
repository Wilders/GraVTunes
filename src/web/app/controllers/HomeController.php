<?php

namespace app\controllers;

use app\helpers\Auth;
use app\models\Commande;
use app\models\Message;
use app\models\Ticket;
use app\models\Track;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class HomeController
 * @package app\controllers
 */
class HomeController extends Controller {

    public function showHome(Request $request, Response $response, array $args): Response {
        $user = Auth::user();
        $tracksUser = Track::where('user_id','=',$user->id)->count();
        $commandesUser = Commande::where('user_id','=',$user->id)->get();
        $messagesUser = Message::where('user_id','=',$user->id)->get();
        if(Auth::check()) {
            $response = $response->withRedirect($this->router->pathFor('appHome',[
               "user" => $user,
               "tracks" => $tracksUser,
               "commandes" => $commandesUser,
               "messages" => $messagesUser
            ]));
        } else {
            $response = $response->withRedirect($this->router->pathFor('login'));
        }
        return $response;
    }
}