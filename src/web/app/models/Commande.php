<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Tracks
 * @package app\models
 */
class Commande extends Model {
    public $timestamps = false;
    protected $table = "commandes";
    protected $primaryKey = "id";

    public function paiements(): HasMany {
        return $this->hasMany('\app\models\Paiement');
    }
}