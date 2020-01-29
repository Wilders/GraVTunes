<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tracks
 * @package app\models
 */
class Tracks extends Model {
    public $timestamps = false;
    protected $table = "tracks";
    protected $primaryKey = "id";
}