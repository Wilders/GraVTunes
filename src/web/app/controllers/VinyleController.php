<?php

namespace app\controllers;

use app\helpers\Auth;
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
        $vinyles = Auth::user()->vinyles;

        $this->view->render($response, 'pages/vinyles.twig', [
            "vinyles" => $vinyles
        ]);
        return $response;
    }

    public function addVinyle(Request $request, Response $response, array $args): Response {
        $tracks = Auth::user()->tracks;

        $this->view->render($response, 'pages/addVinyle.twig', [
            "tracks" => $tracks
        ]);
        return $response;
    }

}