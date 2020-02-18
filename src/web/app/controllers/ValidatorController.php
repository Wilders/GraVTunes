<?php

namespace app\controllers;

use app\exceptions\ValidatorException;
use app\models\User;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class ValidatorController
 * @package app\controllers
 */
class ValidatorController extends Controller {

    const VALID = ['valid' => true, 'error' => ""];

    public static function match($pattern, $input) {
        return preg_match($pattern, $input, $matches) === 1 && $matches[0] === $input;
    }

    public static function pseudo(String $input): array {
        try {
            $length = mb_strlen($input, "utf8");
            if ($length < 3 || $length > 35) {
                throw new ValidatorException("Votre pseudo doit contenir entre 3 et 35 caractères.");
            }
            if(!self::match("/^[A-Za-z0-9]+(?:[._-][A-Za-z0-9]+)*$/", $input)) throw new ValidatorException("Votre pseudo est incorrect.");
            if (User::where(['pseudo' => $input])->exists()) {
                throw new ValidatorException("Ce pseudo est déjà pris.");
            }
            $response = self::VALID;
        } catch(ValidatorException $e) {
            $response = ['valid' => false, 'error' => $e->getMessage()];
        }
        return $response;
    }

    public static function name(String $input): array {
        try {
            $length = mb_strlen($input, "utf8");
            if ($length < 3 || $length > 50) {
                throw new ValidatorException("Votre nom doit contenir entre 3 et 50 caractères.");
            }
            $response = self::VALID;
        } catch(ValidatorException $e) {
            $response = ['valid' => false, 'error' => $e->getMessage()];
        }
        return $response;
    }

    public static function forename(String $input): array {
        try {
            $length = mb_strlen($input, "utf8");
            if ($length < 3 || $length > 50) {
                throw new ValidatorException("Votre nom doit contenir entre 3 et 50 caractères.");
            }
            $response = self::VALID;
        } catch(ValidatorException $e) {
            $response = ['valid' => false, 'error' => $e->getMessage()];
        }
        return $response;
    }

    public static function email(String $input): array {
        try {
            if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
                throw new ValidatorException("Votre adresse e-mail n'est pas valide.");
            }
            if (User::where(["email" => $input])->first()) {
                throw new ValidatorException("Cette adresse e-mail est déjà utilisée.");
            }
            $response = self::VALID;
        } catch(ValidatorException $e) {
            $response = ['valid' => false, 'error' => $e->getMessage()];
        }
        return $response;
    }

    public static function validator(Request $request, Response $response, array $args) {
        try {
            if (!isset($request->getQueryParams()['method']) || !isset($request->getQueryParams()['input'])) {
                throw new ValidatorException("Requête incomplète.");
            }
            $method = $request->getQueryParams()['method'];
            if (!method_exists(self::class, $method)) {
                throw new ValidatorException("La méthode n'existe pas.");
            }
            $input = trim(urldecode($request->getQueryParams()['input']));
            $response = $response->withJson(self::$method($input));
        } catch(ValidatorException $e) {
            $response = ['valid' => false, 'error' => $e->getMessage()];
        }
        return $response;
    }
}