<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package app\models
 */
class Vinyle extends Model {
    public $timestamps = false;
    protected $table = "vinyles";
    protected $primaryKey = "id";
}