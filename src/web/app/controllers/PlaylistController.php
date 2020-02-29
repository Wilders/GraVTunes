<?php

namespace app\controllers;

use app\exceptions\PlaylistException;
use app\helpers\Auth;
use app\models\Track;
use app\models\Playlist;
use app\models\File;

use Carbon\Traits\Date;
use FFMpeg\FFProbe;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Http\Message\UploadedFileInterface;
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

    public function newPlaylist(Request $request, Response $response, array $args) : Response{
        $this->view->render($response, 'pages/addPlaylist.twig');
        return $response;
    }

    public function addPlaylist(Request $request, Response $response, array $args) : Response {
        try{
            $titre = filter_var($request->getParsedBodyParam('name'), FILTER_SANITIZE_SPECIAL_CHARS);
            $descr = filter_var($request->getParsedBodyParam('descr'), FILTER_SANITIZE_SPECIAL_CHARS);

            $ffmpeg = FFProbe::create();
            $ffmpeg = FFProbe::create();
            $playlist = new Playlist();

            $playlist->nom = $titre;
            $playlist->description = $descr;
            $playlist->user_id = Auth::user()->id;
            $playlist->creationDate = \date('Y-m-d');

            $playlist->save();

            $this->flash->addMessage('success',"Félicitations, votre playliste a bien été enregistré. Vous pouvez la consulter dans vos playlistes.");
            $response = $response->withRedirect($this->router->pathFor("appHome"));
        }catch(ModelNotFoundException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }


}


?>