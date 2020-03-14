<?php

namespace app\controllers;

use app\exceptions\AuthException;
use app\helpers\Auth;
use app\models\Track;
use app\models\Vinyle;
use DateTime;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class VinyleController
 * @package app\controllers
 */
class VinyleController extends Controller {

    public function vinyle(Request $request, Response $response, array $args): Response {
        try {
            $vinyle = Vinyle::where(["id" => $args['id'], "user_id" => Auth::user()->id])->firstOrFail();

            $this->view->render($response, 'pages/vinyle.twig', [
                "vinyle" => $vinyle
            ]);
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Ce vinyle n'existe pas.");
            $response = $response->withRedirect($this->router->pathFor('appHome'));
        }
        return $response;
    }

    public function vinyles(Request $request, Response $response, array $args): Response {
        $vinyles = Auth::user()->vinyles;

        $this->view->render($response, 'pages/vinyles.twig', [
            "vinyles" => $vinyles
        ]);
        return $response;
    }

    public function showAddVinyle(Request $request, Response $response, array $args): Response {
        $tracks = Auth::user()->tracks;

        $this->view->render($response, 'pages/addVinyle.twig', [
            "tracks" => $tracks
        ]);
        return $response;
    }

    public function addVinyle(Request $request, Response $response, array $args): Response {
        try {
            $name = filter_var($request->getParsedBodyParam('name'), FILTER_SANITIZE_STRING);
            $description = filter_var($request->getParsedBodyParam('description'), FILTER_SANITIZE_STRING);
            $files = $request->getUploadedFiles();
            $tracks = $request->getParsedBodyParam('tracks');

            if (mb_strlen($name, 'utf8') > 75) throw new AuthException("Votre nom ne doit pas dépasser 75 caractères.");
            if (mb_strlen($description, 'utf8') > 1000) throw new AuthException("Votre description ne doit pas dépasser 1000 caractères.");

            $vinyle = new Vinyle();
            $vinyle->user_id = Auth::user()->id;
            $vinyle->nom = $name;
            $vinyle->shareKey = bin2hex(random_bytes(12));
            $vinyle->locked = false;
            $vinyle->description = $description;
            $vinyle->prix = random_int(10, 50);
            $vinyle->creationDate = new DateTime();

            if(isset($files['cover'])) {
                $cover = $files['cover'];
                if ($cover->getError() === UPLOAD_ERR_OK) {
                    $coversPath = $this->uploadsPath . DIRECTORY_SEPARATOR . "covers";
                    $extension = pathinfo($cover->getClientFilename(), PATHINFO_EXTENSION);
                    $coverFileName = sprintf('%s.%0.8s', bin2hex(random_bytes(8)), $extension);
                    $cover->moveTo($coversPath . DIRECTORY_SEPARATOR . $coverFileName);
                    $vinyle->cover = $coverFileName;
                }
            }
            $vinyle->save();

            $vinyle->tracks()->saveMany(Track::find($tracks));

            $this->flash->addMessage('success', "Les modifications apportées à votre compte ont été enregistrées !");
            $response = $response->withRedirect($this->router->pathFor('appVinyles'));
        } catch (\Exception $e) {

        }
        return $response;
    }

    public function updateVinyle() {

    }

    public function deleteVinyle() {

    }
}