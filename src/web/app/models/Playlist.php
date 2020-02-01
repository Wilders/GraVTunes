<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tracks
 * @package app\models
 */
class Playlist extends Model {
    public $timestamps = false;
    protected $table = "playlists";
    protected $primaryKey = "id";
}