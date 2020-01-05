<?php
namespace App\Config;

use Exception;
use Illuminate\Database\Capsule\Manager as DB;

/**
 * Class Database
 * @package App\Config
 */
class Database {

    /**
     * Config eloquent with a config file
     * @throws Exception
     */
    public static function connect() {
        if (file_exists(__DIR__ . '/database.ini')) {
            $data = parse_ini_file(__DIR__ . '/database.ini');
        } else {
            throw new Exception("Fichier /app/config/database.ini manquant");
        }

        $db = new DB();
        $db->addConnection([
            'driver' => $data['driver'],
            'host' => $data['host'],
            'database' => $data['database'],
            'username' => $data['username'],
            'password' => $data['password'],
            'charset' => $data['charset'],
            'collation' => $data['collation'],
            'prefix' => ''
        ]);
        $db->setAsGlobal();
        $db->bootEloquent();
    }
}