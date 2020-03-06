<?php

namespace app\controllers;

use app\helpers\Auth;
use app\models\Commande;
use app\models\File;
use app\models\Ticket;
use app\models\Track;
use Braintree_ClientToken;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AppController
 * @package app\controllers
 */
class AppController extends Controller {

    public function showHome(Request $request, Response $response, array $args): Response {
        $user = Auth::user();
        $tracksUser = Track::where('user_id','=', $user->id)->get();
        $filesUser  =  [];File::get();
        $commandesUser = Commande::where('user_id','=',$user->id)->get();
        $ticketsUser = Ticket::where(['user_id' => $user->id, 'statut' => 0])->get();
        for($i=0;$i<$tracksUser->count();$i++){
            $filesUser[$i] = File::where('id', '=', $tracksUser[$i]->file_id)->first();
        }
        /*
        foreach ($tracksUser as $t){
            $filesUser = array_push((array)Track::file()->where('i', '=', $t->file_id)->first());//array_push(File::where('id','=',$t->file_id)->first());
        }*/

        $this->view->render($response, 'pages/home.twig', [
            "user" => $user,
            "tracks" => $tracksUser,
            "files" => $filesUser,
            "commandes" => $commandesUser,
            "tickets" => $ticketsUser
        ]);
        return $response;
    }

    public function btToken(Request $request, Response $response, array $args): Response {
        return $response->withJson([
            'token' => Braintree_ClientToken::generate()
        ]);
    }
}