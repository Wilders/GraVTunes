<?php

namespace app\helpers;

use app\models\User;
use Exception;

/**
 * Class Auth
 * @package app\helpers
 */
class Auth {

    public static function attempt($login, $password) {
        try {
            $user = User::where('pseudo', '=', $login)->orWhere('email', '=', $login)->firstOrFail();
            if (!password_verify($password, $user->password)) throw new Exception();
            $_SESSION['user'] = $user;
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function user() {
        $res = null;
        if(self::check()) {
            $_SESSION['user']->refresh();
            $res = $_SESSION['user'];
        }
        return $res;
    }

    public static function check() {
        return isset($_SESSION['user']);
    }

    public static function logout() {
        unset($_SESSION['user']);
    }

}