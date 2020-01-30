<?php

namespace app\controllers;

use app\exceptions\TracksException;
use app\models\Tracks;
use app\models\UserPossede;
use app\models\File;
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

    public function addFile(Request $request, Response $response, array $args) : Response {
        try{
            $file = $request->getUploadedFiles();
            $titre = filter_var($request->getParsedBodyParam('title'), FILTER_SANITIZE_SPECIAL_CHARS);
            $descr = filter_var($request->getParsedBodyParam('descr'), FILTER_SANITIZE_SPECIAL_CHARS);

            $fichier = new File();
            $track = new Tracks();

            $fichier->path = "test";//pathinfo($file->getClientFilename(), PATHINFO_EXTENSION );
            $fichier->hash = "test";//$this->hashage($fichier->getClientFilename());
            $fichier->duree = 0;

            $fichier->save();

            $track->nom = $titre;
            $track->description = $descr;
            $track->file_id = 1;// $fichier->id;

            $track->save();

            $this->flash->addMessage('success',"Félicitations, votre fichier a bien été enregistré. Vous pouvez le consulter dans vos titres.");
            $response = $response->withRedirect($this->router->pathFor("appHome"));
        }catch(TracksException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }

    function moveUploadedFile($directory, UploadedFileInterface $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

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