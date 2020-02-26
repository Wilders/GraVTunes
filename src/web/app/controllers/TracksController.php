<?php

namespace app\controllers;

use app\exceptions\TracksException;
use app\helpers\Auth;
use app\models\Track;
use app\models\File;

use Exception;
use ffmpeg_movie;
use FFMpeg\FFProbe;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Http\Message\UploadedFileInterface;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class TracksController
 * @package app\controllers
 */
class TracksController extends Controller{

    public function showTracks(Request $request, Response $response, array $args) : Response {
        $this->view->render($response, 'pages/tracks.twig');
        return $response;
    }

    public function importTracks(Request $request, Response $response, array $args) : Response {
        $this->view->render($response, 'pages/importTracks.twig');
        return $response;
    }

    public function tracks(Request $request, Response $response, array $args) : Response {
        try{
                $tracks = Track::where('user_id','=',Auth::user()->id)->get();

                $this->view->render($response, 'pages/tracks.twig',[
                    "tracks" => $tracks
                ]);
                return $response;

        }catch (Exception $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
    }

    public function addFile(Request $request, Response $response, array $args) : Response {
        try{
            $file = $request->getUploadedFiles();
            $titre = filter_var($request->getParsedBodyParam('title'), FILTER_SANITIZE_SPECIAL_CHARS);
            $descr = filter_var($request->getParsedBodyParam('descr'), FILTER_SANITIZE_SPECIAL_CHARS);

            $ffmpeg = FFProbe::create();
            $filename = $file["file"]->getClientFilename();
            $path = 'uploads/tracks/'.$filename;
            $extensionOk = array('mp3','avi','wav');
            $extension = substr(strrchr($filename,"."),1);
            //if(!array_search($extension,$extensionOk)) throw new Exception("L'extension du fichier importé ne correspond aux exigences de notre application. Veuillez réessayer ultérieurement.");
            $file["file"]->moveTo('uploads/tracks/' . $filename);
            $ffmpeg = FFProbe::create();
            $trackInfo = $ffmpeg->format($path);

            $fichier = new File();
            $track = new Track();

            $fichier->path = $filename;
            $fichier->hash = $this->hashage($filename);
            $fichier->duree = $trackInfo->get('duration');

            $track->nom = $titre;
            $track->description = $descr;
            $track->file_id = File::count() + 3;
            $track->user_id = Auth::user()->id;

            $track->save();
            $fichier->save();

            $this->flash->addMessage('success',"Félicitations, votre fichier a bien été enregistré. Vous pouvez le consulter dans vos titres.");
            $response = $response->withRedirect($this->router->pathFor("appHome"));
        }catch(ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }

    public function deleteFile(Request $request, Response $response, array $args) : Response {
        try{
            $id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

            $track = Track::where(["id" => $id, "user_id" => Auth::user()->id])->firstOrFail();
            $file = File::where('id','=',$track->file_id)->firstOrFail();

            $track->delete();
            $file->delete();

            $this->flash->addMessage('success',"Vous venez de supprimer ".$track->nom.".");
            $response = $response->withRedirect($this->router->pathFor("appTracks"));
        }catch (ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }

    public function updateFile(Request $request, Response $response, array $args) : Response {
        try{
            $id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);
            $name = filter_var($request->getParsedBodyParam("name"),FILTER_SANITIZE_SPECIAL_CHARS);
            $descr = filter_var($request->getParsedBodyParam("descr"), FILTER_SANITIZE_SPECIAL_CHARS);

            $track = Track::where(["id" => $id, "user_id" => Auth::user()->id])->firstOrFail();

            $track->nom = $name;
            $track->description = $descr;

            $track->save();

            $this->flash->addMessage('success',"Vous venez de modifier les informations de votre chanson.");
            $response = $response->withRedirect($this->router->pathFor("appTracks"));
        }catch(ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }

        return $response;
    }

    public function formUpdateTracks(Request $request, Response $response, array $args) : Response {
        try{
            $id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

            $track = Track::where(["id" => $id, "user_id" => Auth::user()->id])->firstOrFail();

            $this->view->render($response, 'pages/updateTracks.twig',[
                "track" => $track,
                "id" => $args['id']
            ]);
            return $response;

        }catch (ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
    }

    function hashage($data = "", $width=192, $rounds = 3) {
        return substr(
            implode(
                array_map(
                    function ($h) {
                        return str_pad(bin2hex(strrev($h)), 16, "0");
                    },
                    str_split(hash("tiger192,$rounds", $data, true), 8)
                )
            ),
            0, 48-(192-$width)/4
        );
    }

}