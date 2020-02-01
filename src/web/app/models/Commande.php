<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tracks
 * @package app\models
 */
class Commande extends Model {
    public $timestamps = false;
    protected $table = "commandes";
    protected $primaryKey = "id";
}