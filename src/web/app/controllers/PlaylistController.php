<?php

namespace app\controllers;

use app\helpers\Auth;
use app\models\Playlist;
use app\models\Track;
use DateTime;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class PlaylistController
 * @package app\controllers
 */
class PlaylistController extends Controller {

    public function playlists(Request $request, Response $response, array $args): Response {
        $playlist = Auth::user()->playlists;
        $this->view->render($response, 'pages/playlists.twig', [
            "playlists" => $playlist
        ]);
        return $response;
    }

    public function newPlay(Request $request, Response $response){
        $this->view->render($response, 'pages/playlistAdd.twig');
    }

    public function importPlay(Request $request, Response $response, array $args): Response {
        try {
            $titre = filter_var($request->getParsedBodyParam('name'), FILTER_SANITIZE_STRING);
            $descr = filter_var($request->getParsedBodyParam('descr'), FILTER_SANITIZE_STRING);

            $playlist = new Playlist();

            $playlist->nom = $titre;
            $playlist->description = $descr;
            $playlist->user_id = Auth::user()->id;
            $playlist->creationDate = new DateTime();

            $playlist->save();

            $this->flash->addMessage('success', "Votre playlist a bien été créée.");
            $response = $response->withRedirect($this->router->pathFor("showPlaylists", ["id" => $playlist->id]));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }

    public function deletePlay(Request $request, Response $response, array $args): Response {
        try {
            $play = Playlist::where(["id" => $args['id'], "user_id" => Auth::user()->id])->firstOrFail();

            $play->delete();

            $this->flash->addMessage('success', "Vous venez de supprimer " . $play->nom . ".");
            $response = $response->withRedirect($this->router->pathFor("showPlaylists"));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de supprimer cette playlist.");
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }

    public function formUpdatePlay(Request $request, Response $response, array $args) :Response{
        try {
            //$id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

            //$play = Playlist::where(["id" => $id, "user_id" => Auth::user()->id])->firstOrFail();

            $this->view->render($response, 'pages/playlistUpdate.twig',[
                "id" => $args['id']
            ]);
        }catch (ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }

    public function updatePlay(Request $request, Response $response, array $args): Response {
        try {
            $play = Playlist::where(["id" => $args['id'], "user_id" => Auth::user()->id])->firstOrFail();

            $name = filter_var($request->getParsedBodyParam("name"), FILTER_SANITIZE_STRING);
            $descr = filter_var($request->getParsedBodyParam("descr"), FILTER_SANITIZE_STRING);

            $play->nom = $name;
            $play->description = $descr;

            $play->save();

            $this->flash->addMessage('success', "Votre playlist a bien été modifiée.");
            $response = $response->withRedirect($this->router->pathFor("showPlaylists", ["id" => $play->id]));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de modifier cette playlist.");
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }


    public function newTrackPlay(Request $request, Response $response, array $args): Response {
        $tracks = Auth::user()->tracks;
        $this->view->render($response, 'pages/playlistAddTrack.twig', [
            "tracks" => $tracks,
            "id" => $args['id']
        ]);
        return $response;
    }

    public function importTrackPlay(Request $request, Response $response, array $args): Response {
        try {
            $play = Playlist::where(["id" => $args['id'], "user_id" => Auth::user()->id])->firstOrFail();
            $tracks = $request->getParsedBodyParam('file');

            $play->tracks()->saveMany(Auth::user()->tracks->find($tracks));

            $this->flash->addMessage('success', "Félicitations, votre fichier a bien été ajouté à votre playlist. Vous pouvez le consulter depuis votre playliste.");
            $response = $response->withRedirect($this->router->pathFor("showPlaylists"));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }

    /*public function showPlay(Request $request, Response $response, array $args) : Response{
        try {
            $id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);
            $playT= Track::join()where(["playlist_id" => $id])->get();
            $this->view->render($response, 'pages/playlistShowTracks.twig', [
                "t" => $playT
            ]);
        }catch (ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }*/

}