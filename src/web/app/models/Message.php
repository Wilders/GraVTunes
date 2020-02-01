<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tracks
 * @package app\models
 */
class Message extends Model {
    public $timestamps = false;
    protected $table = "messages";
    protected $primaryKey = "id";
}