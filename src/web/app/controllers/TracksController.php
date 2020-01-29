<?php

namespace app\controllers;

use app\exceptions\TracksException;
use app\models\Tracks;
use app\models\UserPossede;
use app\controllers\Auth;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AuthController
 * @author Anthony Pernot <Anthony Pernot>
 * @package app\controllers
 */
class TracksController extends Controller{

    public function showTracks(Request $request, Response $response, array $args) : Response {
        $this->view->render($response, 'pages/tracks.twig');
        return $response;
    }

    public function tracks(Request $request, Response $response, array $args) : Response {
        try{
           // if(Auth::check()){
                //$userPossede = UserPossede::where('user_id','=',1)->firstOrFail();
                //$tracks = Tracks::where('id','=',$userPossede->track_id)->get();
                $tracks = Tracks::select("*")->get();

                $this->view->render($response, 'pages/tracks.twig',[
                    "tracks" => $tracks
                ]);
                return $response;
            //}
        }catch (TracksException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
    }

}