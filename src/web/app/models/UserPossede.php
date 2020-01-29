<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;


class UserPossede extends Model{
    public $timestamps = false;
    protected $table = "users_possede";
    protected $primaryKey = "id";
}