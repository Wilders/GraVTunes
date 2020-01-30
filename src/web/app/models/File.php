<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tracks
 * @package app\models
 */
class File extends Model {
    public $timestamps = false;
    protected $table = "files";
    protected $primaryKey = "id";
}