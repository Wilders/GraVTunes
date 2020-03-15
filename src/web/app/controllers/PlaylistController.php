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

    public function playlist(Request $request, Response $response){
        $this->view->render($response, 'pages/playlist.twig');
    }

    public function playlists(Request $request, Response $response, array $args): Response {
        $playlist = Auth::user()->playlists;

        $this->view->render($response, 'pages/playlists.twig', [
            "playlists" => $playlist
        ]);
        return $response;
    }

    public function showAddPlaylist(Request $request, Response $response) {
        $this->view->render($response, 'pages/addPlaylist.twig');
        return $response;
    }

    public function addPlaylist(Request $request, Response $response, array $args): Response {
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
            $response = $response->withRedirect($this->router->pathFor("showPlaylist", ["id" => $playlist->id]));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }

    public function deletePlaylist(Request $request, Response $response, array $args): Response {
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

    public function showUpdatePlaylist(Request $request, Response $response, array $args): Response {
        $this->view->render($response, 'pages/updatePlaylist.twig', [
            "id" => $args['id']
        ]);
        return $response;
    }

    public function updatePlaylist(Request $request, Response $response, array $args): Response {
        try {
            $play = Playlist::where(["id" => $args['id'], "user_id" => Auth::user()->id])->firstOrFail();

            $name = filter_var($request->getParsedBodyParam("name"), FILTER_SANITIZE_STRING);
            $descr = filter_var($request->getParsedBodyParam("descr"), FILTER_SANITIZE_STRING);

            $play->nom = $name;
            $play->description = $descr;

            $play->save();

            $this->flash->addMessage('success', "Votre playlist a bien été modifiée.");
            $response = $response->withRedirect($this->router->pathFor("showPlaylist", ["id" => $play->id]));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de modifier cette playlist.");
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }


    public function showAddTrackPlaylist(Request $request, Response $response, array $args): Response {
        $tracks = Auth::user()->tracks;

        $this->view->render($response, 'pages/addTrackPlaylist.twig', [
            "tracks" => $tracks,
            "id" => $args['id']
        ]);
        return $response;
    }

    public function addTrackPlaylist(Request $request, Response $response, array $args): Response {
        try {
            $titre = filter_var($request->getParsedBodyParam('title'), FILTER_SANITIZE_SPECIAL_CHARS);
            $descr = filter_var($request->getParsedBodyParam('descr'), FILTER_SANITIZE_SPECIAL_CHARS);

            $track->id = Track::count() + 1;
            $track->nom = $titre;
            $track->description = $descr;
            $track->file_id = File::count() + 1;
            $track->user_id = Auth::user()->id;

            $track->save();
            $fichier->save();

            $this->flash->addMessage('success', "Félicitations, votre fichier a bien été ajouté à votre playlist. Vous pouvez le consulter depuis votre playliste.");
            $response = $response->withRedirect($this->router->pathFor("appPlaylist"));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }
}