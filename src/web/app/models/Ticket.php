<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tracks
 * @package app\models
 */
class Ticket extends Model {
    public $timestamps = false;
    protected $table = "tickets";
    protected $primaryKey = "id";
}