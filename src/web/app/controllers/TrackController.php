<?php

namespace app\controllers;

use app\helpers\Auth;
use app\models\File;
use app\models\Track;
use FFMpeg\FFProbe;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class TrackController
 * @package app\controllers
 */
class TrackController extends Controller{

    public function tracks(Request $request, Response $response, array $args): Response {
        $tracks = Auth::user()->tracks()->where("archived", false)->get();

        $this->view->render($response, 'pages/tracks.twig', [
            "tracks" => $tracks
        ]);
        return $response;
    }

    public function showAddTrack(Request $request, Response $response, array $args): Response {
        $this->view->render($response, 'pages/addTrack.twig');
        return $response;
    }


    /**
     * @todo: Vérification du type de fichiers uploadés
     */
    public function addTrack(Request $request, Response $response, array $args): Response {
        $files = $request->getUploadedFiles();
        $titre = filter_var($request->getParsedBodyParam('title'), FILTER_SANITIZE_SPECIAL_CHARS);
        $descr = filter_var($request->getParsedBodyParam('descr'), FILTER_SANITIZE_SPECIAL_CHARS);

        $uploadedTrack = $files['file'];
        if ($uploadedTrack->getError() === UPLOAD_ERR_OK) {
            $path = $this->uploadsPath . DIRECTORY_SEPARATOR . "tracks";
            $extension = pathinfo($uploadedTrack->getClientFilename(), PATHINFO_EXTENSION);
            $hash = hash_file("md5", $uploadedTrack->file);
            $name = sprintf('%s.%0.8s', $hash, $extension);
            $endFilePath = $path . DIRECTORY_SEPARATOR . $name;
            $uploadedTrack->moveTo($endFilePath);
        }

        $ffmpeg = FFProbe::create();
        $trackInfo = $ffmpeg->format($endFilePath);

        if(!File::where('hash', $hash)->exists()) {
            $fichier = new File();
            $fichier->path = $name;
            $fichier->hash = $hash;
            $fichier->duree = $trackInfo->get('duration');
            $fichier->size = $uploadedTrack->getSize();
            $fichier->save();
        } else {
            $fichier = File::where('hash', $hash)->firstOrFail();
        }

        $track = new Track();
        $track->nom = $titre;
        $track->description = $descr;
        $track->file_id = $fichier->id;
        $track->user_id = Auth::user()->id;
        $track->save();

        $this->flash->addMessage('success', "Votre titre a bien été importé.");
        $response = $response->withRedirect($this->router->pathFor("showTracks"));
        return $response;
    }

    /**
     * @todo: Le titre doit être archivé, supprimé des playlists et supprimé des vinyles qui ne sont pas locked
     */
    public function deleteTrack(Request $request, Response $response, array $args): Response {
        try {
            $track = Track::where(["id" => $args['id'], "user_id" => Auth::user()->id])->firstOrFail();

            $track->archived = true;
            $track->save();

            $this->flash->addMessage('success', "Vous venez de supprimer " . $track->nom . ".");
            $response = $response->withRedirect($this->router->pathFor("showTracks"));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de supprimer ce titre");
            $response = $response->withRedirect($this->router->pathFor("showTracks"));
        }
        return $response;
    }

    public function updateTrack(Request $request, Response $response, array $args): Response {
        try {
            $name = filter_var($request->getParsedBodyParam("name"), FILTER_SANITIZE_STRING);
            $descr = filter_var($request->getParsedBodyParam("descr"), FILTER_SANITIZE_STRING);

            $track = Track::where(["id" => $args['id'], "user_id" => Auth::user()->id])->firstOrFail();

            $track->nom = $name;
            $track->description = $descr;
            $track->save();

            $this->flash->addMessage('success', "Vous venez de modifier les informations de votre titre.");
            $response = $response->withRedirect($this->router->pathFor("showTracks"));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de modifier ce titre.");
            $response = $response->withRedirect($this->router->pathFor("showTracks"));
        }

        return $response;
    }

    public function showUpdateTrack(Request $request, Response $response, array $args): Response {
        try {
            $track = Track::where(["id" => $args['id'], "user_id" => Auth::user()->id])->firstOrFail();

            $this->view->render($response, 'pages/updateTrack.twig', [
                "track" => $track,
                "id" => $args['id']
            ]);
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de modifier ce titre.");
            $response = $response->withRedirect($this->router->pathFor("showTracks"));
        }
        return $response;
    }
}