<?php

namespace app\controllers;

use app\exceptions\AuthException;
use app\models\Commande;
use app\models\Ticket;
use app\models\Track;
use app\models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AdminController
 * @package app\controllers
 */
class AdminController extends Controller {

    public function showHome(Request $request, Response $response, array $args): Response {
        $files = [];
        foreach (Track::where(['archived' => false])->get() as $track) {
            $files[] = $track->file->size;
        }

        $this->view->render($response, 'pages/admin/home.twig', [
            "users" => User::all(),
            "tracks" => Track::where(['archived' => false])->get(),
            "orders" => Commande::all(),
            "tickets" => Ticket::all(),
            "arroundDiskSpace" => round(array_sum($files) / (1024 * 1024), 2)
        ]);
        return $response;
    }

    public function deleteTrack(Request $request, Response $response, array $args): Response {
        try {
            $track = Track::where(["id" => $args['id']])->firstOrFail();

            $track->archived = true;
            $track->save();

            $this->flash->addMessage('success', "Vous venez de supprimer " . $track->nom . ".");
            $response = $response->withRedirect($this->router->pathFor("showAdminHome"));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de supprimer ce titre");
            $response = $response->withRedirect($this->router->pathFor("showAdminHome"));
        }
        return $response;
    }

    public function nextStepOrder(Request $request, Response $response, array $args): Response {
        try {
            $order = Commande::where('id', $args['id'])->firstOrFail();
            if ($order->statut < 5) {
                $order->statut += 1;
            }
            $order->save();

            $this->flash->addMessage('success', "Vous avez fait évoluer la commande #$order->id.");
            $response = $response->withRedirect($this->router->pathFor("showAdminHome"));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de faire évoluer cette commande.");
            $response = $response->withRedirect($this->router->pathFor("showAdminHome"));
        }
        return $response;
    }

    public function showTicket(Request $request, Response $response, array $args): Response {
        try {
            $ticket = Ticket::where('id', $args['id'])->firstOrFail();
            $messages = $ticket->messages;

            $this->view->render($response, 'pages/ticket.twig', [
                "messages" => $messages,
                "ticket" => $ticket
            ]);
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de trouver ce ticket.");
            $response = $response->withRedirect($this->router->pathFor("showAdminHome"));
        }
        return $response;
    }

    public function showOrder(Request $request, Response $response, array $args): Response {
        try {
            $order = Commande::where(["id" => $args['id']])->firstOrFail();

            $this->view->render($response, 'pages/order.twig', [
                "order" => $order
            ]);
            return $response;
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de trouver cette commande.");
            $response = $response->withRedirect($this->router->pathFor("showAdminHome"));
        }
        return $response;
    }

    public function closeTicket(Request $request, Response $response, array $args): Response {
        try {
            $ticket = Ticket::where('id', $args['id'])->firstOrFail();

            $ticket->statut = 1;
            $ticket->save();

            $this->flash->addMessage('success', "Vous avez clos le ticket #$ticket->id.");
            $response = $response->withRedirect($this->router->pathFor("showAdminHome"));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de clore ce ticket.");
            $response = $response->withRedirect($this->router->pathFor("showAdminHome"));
        }
        return $response;
    }

    public function updateUser(Request $request, Response $response, array $args): Response {
        try {
            $id = filter_var($request->getParsedBodyParam('id'), FILTER_SANITIZE_NUMBER_INT);
            $pseudo = filter_var($request->getParsedBodyParam('pseudo'), FILTER_SANITIZE_STRING);
            $name = filter_var($request->getParsedBodyParam('name'), FILTER_SANITIZE_STRING);
            $forename = filter_var($request->getParsedBodyParam('forename'), FILTER_SANITIZE_STRING);
            $address = filter_var($request->getParsedBodyParam('address'), FILTER_SANITIZE_STRING);
            $email = filter_var($request->getParsedBodyParam('email'), FILTER_SANITIZE_EMAIL);

            $user = User::where(['id' => $id])->firstOrFail();

            if (mb_strlen($pseudo, 'utf8') < 3 || mb_strlen($pseudo, 'utf8') > 35) throw new AuthException("Votre pseudo doit contenir entre 3 et 35 caractères.");
            if (mb_strlen($name, 'utf8') < 2 || mb_strlen($name, 'utf8') > 50) throw new AuthException("Votre nom doit contenir entre 2 et 50 caractères.");
            if (mb_strlen($forename, 'utf8') < 2 || mb_strlen($forename, 'utf8') > 50) throw new AuthException("Votre prénom doit contenir entre 2 et 50 caractères.");
            if (User::where('pseudo', '=', $pseudo)->exists()) throw new AuthException("Ce pseudo est déjà pris.");

            if ($user->email != $email) {
                if (User::where('email', '=', $email)->exists()) throw new AuthException("Cet email est déjà utilisée.");
            }

            if ($user->pseudo != $pseudo) {
                if (User::where('pseudo', '=', $pseudo)->exists()) throw new AuthException("Ce nom d'utilisateur est déjà utilisée.");
            }

            $user->pseudo = $pseudo;
            $user->nom = $name;
            $user->prenom = $forename;
            $user->email = $email;
            $user->address = $address;
            $user->save();

            $this->flash->addMessage('success', "Les modifications apportées à $user->pseudo ont été enregistrées !");
            $response = $response->withRedirect($this->router->pathFor('showAdminHome'));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', "Impossible de trouver cet utilisateur.");
            $response = $response->withRedirect($this->router->pathFor("showAdminHome"));
        } catch (AuthException $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor("showAdminHome"));
        }
        return $response;
    }
}