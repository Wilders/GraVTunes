<?php

namespace app\controllers;

use app\exceptions\PlaylistException;
use app\helpers\Auth;
use app\models\Track;
use app\models\Playlist;
use app\models\File;

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

        }catch (PlaylistException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
    }


}


?>