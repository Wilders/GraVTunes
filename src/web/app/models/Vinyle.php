<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Vinyle
 * @package app\models
 */
class Vinyle extends Model {
    public $timestamps = false;
    protected $table = "vinyles";
    protected $primaryKey = "id";

    public $quantity = null;

    public function user(): BelongsTo {
        return $this->belongsTo("\app\models\User");
    }

    public function tracks(): BelongsToMany {
        return $this->belongsToMany("\app\models\Track");
    }

    public function commandes(): BelongsToMany {
        return $this->belongsToMany("\app\models\Commande");
    }
}