<?php

namespace app\controllers;

use app\helpers\Auth;
use app\models\Playlist;
use app\models\Track;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class PlaylistController
 * @package app\controllers
 */
class PlaylistController extends Controller {

    public function showPlaylist(Request $request, Response $response){
        $this->view->render($response, 'pages/playlist.twig');
    }

    public function newPlay(Request $request, Response $response){
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

    public function importPlay(Request $request, Response $response, array $args) : Response {
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

    public function formUpdatePlay(Request $request, Response $response, array $args) :Response{
        try {
            $id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

            $play = Playlist::where(["id" => $id, "user_id" => Auth::user()->id])->firstOrFail();

            $this->view->render($response, 'pages/updatePlaylist.twig',[
                "id" => $args['id']
            ]);
            return $response;
        }catch (ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
    }

    public function updatePlay(Request $request, Response $response, array $args) : Response {
        try{
            $id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);
            $name = filter_var($request->getParsedBodyParam("name"),FILTER_SANITIZE_SPECIAL_CHARS);
            $descr = filter_var($request->getParsedBodyParam("descr"), FILTER_SANITIZE_SPECIAL_CHARS);

            $play = Playlist::where(["id" => $id, "user_id" => Auth::user()->id])->firstOrFail();

            $play->nom = $name;
            $play->description = $descr;

            $play->save();

            $this->flash->addMessage('success',"Vous venez de modifier les informations de votre playliste.");
            $response = $response->withRedirect($this->router->pathFor("appPlaylist"));
        }catch(ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }

        return $response;
    }


    public function formNewTrack(Request $request, Response $response, array $args) :Response{
        try {
            $tracks= Track::where (["user_id" => Auth::user()->id])->get();

            $id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

            $play = Playlist::where(["id" => $id, "user_id" => Auth::user()->id])->firstOrFail();

            $this->view->render($response, 'pages/addTrackPlaylist.twig',[
                "tracks" => $tracks,
                "id" => $args['id']
            ]);
            return $response;
        }catch (ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
    }

    public function importTrackPlay(Request $request, Response $response, array $args) : Response {
        try{
            $titre = filter_var($request->getParsedBodyParam('title'), FILTER_SANITIZE_SPECIAL_CHARS);
            $descr = filter_var($request->getParsedBodyParam('descr'), FILTER_SANITIZE_SPECIAL_CHARS);


            $track->id = Track::count()+1;
            $track->nom = $titre;
            $track->description = $descr;
            $track->file_id = File::count() + 1;
            $track->user_id = Auth::user()->id;

            $track->save();
            $fichier->save();

            $this->flash->addMessage('success',"Félicitations, votre fichier a bien été ajouté à votre playliste. Vous pouvez le consulter depuis votre playliste.");
            $response = $response->withRedirect($this->router->pathFor("appPlaylist"));
        }catch(ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }

}


?>