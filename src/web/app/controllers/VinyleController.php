<?php

namespace app\controllers;

use app\exceptions\VinyleException;
use app\helpers\Auth;
use app\models\Track;
use app\models\Playlist;
use app\models\File;
use app\models\Vinyle;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class VinyleController
 * @author Anthony Pernot <Anthony Pernot>
 * @package app\controllers
 */
class VinyleController extends Controller {

    public function showVinyle(Request $request, Response $response, array $args) : Response{
        $this->view->render($response, 'pages/vinyle.twig');
        return $response;
    }

    public function vinyles(Request $request, Response $response, array $args) : Response {
        try{
            $vinyle = Vinyle::where('user_id','=',Auth::user()->id)->get();

            $this->view->render($response, 'pages/vinyle.twig',[
                "vinyles" => $vinyle
            ]);
            return $response;

        }catch (VinyleException $e){
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
    }


}


?>