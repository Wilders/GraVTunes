<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    public $timestamps = false;
    protected $table = "users";
    protected $primaryKey = "id";
}