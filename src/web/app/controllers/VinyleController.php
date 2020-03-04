<?php

namespace app\controllers;

use app\helpers\Auth;
use app\models\Vinyle;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class VinyleController
 * @package app\controllers
 */
class VinyleController extends Controller {

    public function showVinyle(Request $request, Response $response, array $args): Response {
        $this->view->render($response, 'pages/vinyle.twig');
        return $response;
    }

    public function vinyles(Request $request, Response $response, array $args): Response {
        try {
            $vinyle = Vinyle::where('user_id', '=', Auth::user()->id)->get();

            $this->view->render($response, 'pages/vinyle.twig', [
                "vinyles" => $vinyle
            ]);
            return $response;

        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
    }

    public function addVinyle(Request $request, Response $response, array $args): Response {
        $this->view->render($response, 'pages/addVinyle.twig');
        return $response;
    }

}