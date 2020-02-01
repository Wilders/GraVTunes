<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tracks
 * @package app\models
 */
class Paiement extends Model {
    public $timestamps = false;
    protected $table = "paiements";
    protected $primaryKey = "id";
}