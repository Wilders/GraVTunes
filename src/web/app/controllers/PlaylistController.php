<?php

namespace app\controllers;

use app\helpers\Auth;
use DateTime;
use Exception;
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

    public function playlist(Request $request, Response $response, array $args) : Response{
        try {
            $playlist = Auth::user()->playlists()->where('id', $args['id'])->firstOrFail();

            $this->view->render($response, 'pages/playlist.twig', [
                "playlist" => $playlist,
                "addableTracks" => Auth::user()->tracks->diff($playlist->tracks)
            ]);
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Cette playlist n'existe pas.");
            $response = $response->withRedirect($this->router->pathFor('showPlaylists'));
        }
        return $response;
    }

    public function showAddPlaylist(Request $request, Response $response, array $args) : Response {
        $this->view->render($response, 'pages/addPlaylist.twig');
        return $response;
    }

    public function addPlaylist(Request $request, Response $response, array $args): Response {
        try {
            $titre = filter_var($request->getParsedBodyParam('name'), FILTER_SANITIZE_STRING);
            $descr = filter_var($request->getParsedBodyParam('descr'), FILTER_SANITIZE_STRING);

            if (mb_strlen($titre, 'utf8') > 75) throw new Exception("Le nom de la playlist ne doit pas dépasser 75 caractères.");
            if (mb_strlen($descr, 'utf8') > 1000) throw new Exception("La description de la playlist ne doit pas dépasser 1000 caractères.");

            Auth::user()->playlists()->create([
                'nom' => $titre,
                'description' => $descr,
                'creationDate' => new DateTime()
            ]);

            $this->flash->addMessage('success', "Votre playlist a bien été créée.");
            $response = $response->withRedirect($this->router->pathFor("showPlaylists"));
        } catch (Exception $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor('showAddPlaylist'));
        }

        return $response;
    }

    public function updatePlaylist(Request $request, Response $response, array $args): Response {
        try {
            $name = filter_var($request->getParsedBodyParam("name"), FILTER_SANITIZE_STRING);
            $descr = filter_var($request->getParsedBodyParam("descr"), FILTER_SANITIZE_STRING);

            if (mb_strlen($name, 'utf8') > 75) throw new Exception("Le nom de la playlist ne doit pas dépasser 75 caractères.");
            if (mb_strlen($descr, 'utf8') > 1000) throw new Exception("La description de la playlist ne doit pas dépasser 1000 caractères.");

            $playlist = Auth::user()->playlists()->where('id', $args['id'])->firstOrFail();

            $playlist->nom = $name;
            $playlist->description = $descr;

            $playlist->save();

            $this->flash->addMessage('success', "Votre playlist a bien été modifiée.");
            $response = $response->withRedirect($this->router->pathFor("showPlaylist", ["id" => $playlist->id]));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de modifier votre playlist.");
            $response = $response->withRedirect($this->router->pathFor('showPlaylist', ["id" => $args["id"] ]));
        } catch (Exception $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor('showPlaylist', ["id" => $args["id"] ]));
        }
        return $response;
    }

    public function deletePlaylist(Request $request, Response $response, array $args): Response {
        try {
            $playlist = Auth::user()->playlists()->where('id', $args['id'])->firstOrFail();

            $playlist->tracks()->detach();
            $playlist->delete();

            $this->flash->addMessage('success', "Vous venez de supprimer la playlist " . $playlist->nom . ".");
            $response = $response->withRedirect($this->router->pathFor("showPlaylists"));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de supprimer votre playlist.");
            $response = $response->withRedirect($this->router->pathFor('showPlaylist', ["id" => $args["id"] ]));
        }
        return $response;
    }

    public function addTracksPlaylist(Request $request, Response $response, array $args): Response {
        try {
            $tracks = $request->getParsedBodyParam('tracks');

            $playlist = Auth::user()->playlists()->where('id', $args['id'])->firstOrFail();

            $playlist->tracks()->saveMany(Auth::user()->tracks->find($tracks));

            $this->flash->addMessage('success', "Vos titres ont bien été ajouté à votre playlist.");
            $response = $response->withRedirect($this->router->pathFor("showPlaylist", ["id" => $playlist->id]));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible d'ajouter des titres à votre playlist.");
            $response = $response->withRedirect($this->router->pathFor('showPlaylist', ["id" => $args["id"] ]));
        }
        return $response;
    }

    public function deleteAttachedTrack(Request $request, Response $response, array $args): Response {
        try {
            $playlist = Auth::user()->playlists()->where('id', $args['id'])->firstOrFail();

            if(!$playlist->tracks->contains($args['trackId'])) throw new ModelNotFoundException();

            $playlist->tracks()->detach($args['trackId']);

            $this->flash->addMessage('success', "Le titre a bien été supprimé de la playlist.");
            $response = $response->withRedirect($this->router->pathFor('showPlaylist', ['id' => $args['id']]));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de supprimer ce titre pour cette playlist.");
            $response = $response->withRedirect($this->router->pathFor('showPlaylist', ['id' => $args['id']]));
        }
        return $response;
    }
}