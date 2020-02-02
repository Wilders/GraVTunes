<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class User
 * @package app\models
 */
class User extends Model {
    public $timestamps = false;
    protected $table = "users";
    protected $primaryKey = "id";

    public function tracks(): HasMany {
        return $this->hasMany("\app\models\Track");
    }

    public function playlists(): HasMany {
        return $this->hasMany("\app\models\Playlist");
    }

    public function vinyles(): HasMany {
        return $this->hasMany("\app\models\Vinyle");
    }

    public function tickets(): HasMany {
        return $this->hasMany("\app\models\Ticket");
    }

    public function commandes(): HasMany {
        return $this->hasMany("\app\models\Commande");
    }
}