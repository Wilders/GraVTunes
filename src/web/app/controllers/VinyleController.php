<?php

namespace app\controllers;

use app\helpers\Auth;
use app\helpers\Basket;
use app\models\Vinyle;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
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
                "vinyle" => $vinyle,
                "uri" => $request->getUri(),
                "addableTracks" => Auth::user()->tracks->diff($vinyle->tracks)
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
            "vinyles" => $vinyles,
            "uri" => $request->getUri()
        ]);
        return $response;
    }

    public function vinyleCollab(Request $request, Response $response, array $args): Response {
        try {
            $vinyle = Vinyle::where(["shareKey" => $args['shareKey']] )->firstOrFail();
            $tracks = Auth::user()->tracks->diff($vinyle->tracks);

            $this->view->render($response, 'pages/vinyleCollab.twig',[
                "vinyle" => $vinyle,
                "tracks" => $tracks
            ]);
        } catch(ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Ce vinyle n'existe pas.");
            $response = $response->withRedirect($this->router->pathFor('showHome'));
        }
        return $response;
    }

    public function showAddVinyle(Request $request, Response $response, array $args): Response {
        $tracks = Auth::user()->tracks;

        $this->view->render($response, 'pages/addVinyle.twig', [
            "tracks" => $tracks
        ]);
        return $response;
    }

    public function getVinyleCollab(Request $request, Response $response, array $args): Response {
        try {
            $key = filter_var($request->getParsedBodyParam('shareKey'), FILTER_SANITIZE_STRING);

            $vinyle = Vinyle::where(["shareKey" => $key])->firstOrFail();
            $response = $response->withRedirect($this->router->pathFor('showCollab', ['shareKey' => $vinyle->shareKey]));
        } catch(ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Ce vinyle n'existe pas.");
            $response = $response->withRedirect($this->router->pathFor('showVinyles'));
        }
        return $response;
    }

    public function addVinyle(Request $request, Response $response, array $args): Response {
        try {
            $name = filter_var($request->getParsedBodyParam('name'), FILTER_SANITIZE_STRING);
            $description = filter_var($request->getParsedBodyParam('description'), FILTER_SANITIZE_STRING);
            $files = $request->getUploadedFiles();
            $tracks = $request->getParsedBodyParam('tracks');

            if (mb_strlen($name, 'utf8') > 75) throw new Exception("Le nom du vinyle ne doit pas dépasser 75 caractères.");
            if (mb_strlen($description, 'utf8') > 1000) throw new Exception("La description du vinyle ne doit pas dépasser 1000 caractères.");

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

            $vinyle->tracks()->saveMany(Auth::user()->tracks->find($tracks));

            $this->flash->addMessage('success', "Votre vinyle a bien été créé!");
            $response = $response->withRedirect($this->router->pathFor('showVinyle', ['id' => $vinyle->id]));
        } catch (Exception $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor('showVinyle', ['id' => $args['id']]));
        }
        return $response;
    }

    public function addTracks(Request $request, Response $response, array $args): Response {
        try {
            $vinyle = Vinyle::where(["id" => $args['id'], "user_id" => Auth::user()->id])->firstOrFail();
            $tracks = $request->getParsedBodyParam('tracks');

            $vinyle->tracks()->saveMany(Auth::user()->tracks->find($tracks));

            $this->flash->addMessage('success', "Vos titres on bien été ajoutés");
            $response = $response->withRedirect($this->router->pathFor('showVinyle', ["id" => $vinyle->id]));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de trouver ce vinyle.");
            $response = $response->withRedirect($this->router->pathFor('showVinyles'));
        }

        return $response;
    }

    public function addTracksCollab(Request $request, Response $response, array $args): Response {
        try {
            $vinyle = Vinyle::where([ "shareKey" => $args['shareKey'] ])->firstOrFail();
            $tracks = $request->getParsedBodyParam('tracks');

            $vinyle->tracks()->saveMany(Auth::user()->tracks->find($tracks));

            $this->flash->addMessage('success', "Vos titres on bien été ajoutés");
            $response = $response->withRedirect($this->router->pathFor('showCollab', ["shareKey" => $vinyle->shareKey]));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de trouver ce vinyle.");
            $response = $response->withRedirect($this->router->pathFor('showVinyles'));
        }
        return $response;
    }

    public function sendInvitCollab(Request $request, Response $response, array $args): Response {
        try {
            $vinyle = Vinyle::where([ "shareKey" => $args['shareKey'] ])->firstOrFail();
            $mailDest = filter_var($request->getParsedBodyParam('mailDest'), FILTER_SANITIZE_STRING);

            $mail = new PHPMailer();
            $mail->setFrom('anthony.pernot@hotmail.fr', 'Anthony PERNOT Serveur');
            $mail->addAddress('anthony.pernot@hotmail.fr', 'Anthony PERNOT Client');
            $mail->Subject  = 'First PHPMailer Message';
            $mail->isHTML(true);
            $mail->Body     = "Hi! This is my first e-mail <br> sent through PHPMailer.";
            if(!$mail->send()) {
                $this->flash->addMessage('error', "Impossible de trouver l'adresse mail : ".$mailDest.".");
                $response = $response->withRedirect($this->router->pathFor('showVinyles'));
            } else {
                $this->flash->addMessage('success', "Le mail d'invitation à collaborer a bien été envoyer à ". $mailDest ." ! ");
                $response = $response->withRedirect($this->router->pathFor('showVinyles'));
            }

        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de trouver ce vinyle.");
            $response = $response->withRedirect($this->router->pathFor('showVinyles'));
        } catch (PHPMailerException $e) {
            $this->flash->addMessage('error', "Impossible d'envoyer le mail, contactez le support pour avoir plus d'information. ");
            $response = $response->withRedirect($this->router->pathFor('showVinyles'));
        }
        return $response;
    }

    public function updateVinyle(Request $request, Response $response, array $args): Response {
        try {
            $vinyle = Vinyle::where(["id" => $args['id'], "user_id" => Auth::user()->id])->firstOrFail();
            $name = filter_var($request->getParsedBodyParam('name'), FILTER_SANITIZE_STRING);
            $description = filter_var($request->getParsedBodyParam('description'), FILTER_SANITIZE_STRING);
            $files = $request->getUploadedFiles();

            if (mb_strlen($name, 'utf8') > 75) throw new Exception("Le nom du vinyle ne doit pas dépasser 75 caractères.");
            if (mb_strlen($description, 'utf8') > 1000) throw new Exception("La description du vinyle ne doit pas dépasser 1000 caractères.");

            $vinyle->nom = $name;
            $vinyle->description = $description;

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

            $this->flash->addMessage('success', "Votre vinyle a bien été modifié!");
            $response = $response->withRedirect($this->router->pathFor('showVinyle', ['id' => $vinyle->id]));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de modifier ce vinyle.");
            $response = $response->withRedirect($this->router->pathFor('showVinyle', ['id' => $args['id']]));
        } catch (Exception $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor('showVinyle', ['id' => $args['id']]));
        }
        return $response;
    }

    /**
     * @todo: Test si le vinyle est locked ou pas.
     */
    public function deleteVinyle(Request $request, Response $response, array $args): Response {
        try {
            $vinyle = Vinyle::where(["id" => $args['id'], "user_id" => Auth::user()->id])->firstOrFail();

            Basket::remove($vinyle);
            $vinyle->tracks()->detach();
            $vinyle->delete();

            $this->flash->addMessage('success', "Le vinyle $vinyle->nom a bien été supprimé.");
            $response = $response->withRedirect($this->router->pathFor('showVinyles'));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de supprimer ce vinyle.");
            $response = $response->withRedirect($this->router->pathFor('showVinyle', ['id' => $args['id']]));
        }
        return $response;
    }

    /**
     * @todo: Test si le vinyle est locked ou pas.
     */
    public function deleteAttachedTrack(Request $request, Response $response, array $args): Response {
        try {
            $vinyle = Vinyle::where(["id" => $args['id'], "user_id" => Auth::user()->id])->firstOrFail();

            if(!$vinyle->tracks->contains($args['trackId'])) throw new ModelNotFoundException();

            $vinyle->tracks()->detach($args['trackId']);

            $this->flash->addMessage('success', "Le titre a bien été supprimé du vinyle.");
            $response = $response->withRedirect($this->router->pathFor('showVinyle', ['id' => $args['id']]));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de supprimer ce titre pour ce vinyle.");
            $response = $response->withRedirect($this->router->pathFor('showVinyle', ['id' => $args['id']]));
        }
        return $response;
    }
}