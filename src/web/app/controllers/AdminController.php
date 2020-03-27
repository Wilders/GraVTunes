<?php

namespace app\controllers;

use app\models\Commande;
use app\models\Ticket;
use app\models\Track;
use app\models\User;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AdminController
 * @package app\controllers
 */
class AdminController extends Controller {

    public function showHome(Request $request, Response $response, array $args): Response {
        $files = [];
        foreach (Track::all() as $track) {
            $files[] = $track->file->size;
        }
        $this->view->render($response, 'pages/admin/home.twig', [
            "users" => User::all(),
            "tracks" => Track::all(),
            "orders" => Commande::all(),
            "tickets" => Ticket::all(),
            "arroundDiskSpace" => round(array_sum($files)/(1024*1024),2)
        ]);
        return $response;
    }
}