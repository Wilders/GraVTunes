<?php

namespace app\controllers;

use app\exceptions\TracksException;
use app\helpers\Auth;
use app\models\Track;
use app\models\File;

use Psr\Http\Message\UploadedFileInterface;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class TracksController
 * @author Anthony Pernot <Anthony Pernot>
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

        }catch (TracksException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
    }

    public function addFile(Request $request, Response $response, array $args) : Response {
        try{
            $file = $request->getUploadedFiles();
            $titre = filter_var($request->getParsedBodyParam('title'), FILTER_SANITIZE_SPECIAL_CHARS);
            $descr = filter_var($request->getParsedBodyParam('descr'), FILTER_SANITIZE_SPECIAL_CHARS);

            $fichier = new File();
            $track = new Track();

            $fichier->path = $file["file"]->getClientFilename();
            $fichier->hash = $this->hashage($file["file"]->getClientFilename());
            $fichier->duree = 0;


            $fichier->save();

            $track->nom = $titre;
            $track->description = $descr;
            $track->file_id = 11;//File::count()->get() + 1;
            $track->user_id = Auth::user()->id;

            $track->save();

            $this->moveUploadedFile( 'uploads/tracks', $file["file"]);

            $this->flash->addMessage('success',"Félicitations, votre fichier a bien été enregistré. Vous pouvez le consulter dans vos titres.");
            $response = $response->withRedirect($this->router->pathFor("appHome"));
        }catch(TracksException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }

    public function deleteFile(Request $request, Response $response, array $args) : Response {
        try{

            $track = Track::where('user_id','=', Auth::user()->id)->first();
            $file = File::where('id','=',$track->file_id)->first();

            $file->delete();
            $track->delete();

            $this->flash->addMessage('success',"Vous venez de supprimer ".$track->nom.".");
            $response = $response->withRedirect($this->router->pathFor("appTracks"));
        }catch (TracksException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }

    public function updateFile(Request $request, Response $response, array $args) : Response {
        try{
            $name = filter_var($request->getParsedBodyParam("name"),FILTER_SANITIZE_SPECIAL_CHARS);
            $descr = filter_var($request->getParsedBodyParam("descr"), FILTER_SANITIZE_SPECIAL_CHARS);

            $track = Track::where("user_id","=",Auth::user()->id)->first();

            $track->nom = $name;
            $track->description = $descr;

            $track->save();

            $this->flash->addMessage('success',"Vous venez de modifier les informations de votre chanson.");
            $response = $response->withRedirect($this->router->pathFor("appTracks"));
        }catch(TracksException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }

        return $response;
    }

    function moveUploadedFile($directory, UploadedFileInterface $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        //$basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', /*$basename,*/ $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
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