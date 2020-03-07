<?php

namespace app\controllers;

use app\helpers\Auth;
use app\models\Playlist;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class PlaylistController
 * @package app\controllers
 */
class PlaylistController extends Controller {

    public function showPlaylist(Request $request, Response $response, array $args) : Response{
        $this->view->render($response, 'pages/playlist.twig');
        return $response;
    }

    public function newPlaylist(Request $request, Response $response){
        $this->view->render($response, 'pages/addPlaylist.twig');
    }

    public function playlists(Request $request, Response $response, array $args) : Response {
        try{
            $playlist = Playlist::where('user_id','=',Auth::user()->id)->get();

            $this->view->render($response, 'pages/playlist.twig',[
                "playlists" => $playlist
            ]);
            return $response;

        }catch (ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
    }

    public function addPlaylist(Request $request, Response $response, array $args) : Response {
        try{
            $titre = filter_var($request->getParsedBodyParam('name'), FILTER_SANITIZE_SPECIAL_CHARS);
            $descr = filter_var($request->getParsedBodyParam('descr'), FILTER_SANITIZE_SPECIAL_CHARS);

            $playlist = new Playlist();

            $playlist->nom = $titre;
            $playlist->description = $descr;
            $playlist->user_id = Auth::user()->id;
            $playlist->creationDate = \date('Y-m-d');

            $playlist->save();

            $this->flash->addMessage('success',"Félicitations, votre playlist a bien été enregistré. Vous pouvez la consulter dans vos playlists. ");
            $response = $response->withRedirect($this->router->pathFor("appPlaylist"));
        }catch(ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }

    public function delPlay(Request $request, Response $response, array $args) : Response {
        try{
            $id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

            $play = Playlist::where(["id" => $id, "user_id" => Auth::user()->id])->firstOrFail();

            $play->delete();

            $this->flash->addMessage('success',"Vous venez de supprimer ".$play->nom.".");
            $response = $response->withRedirect($this->router->pathFor("appPlaylist"));
        }catch (ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }


}


?>