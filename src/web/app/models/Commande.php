<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Commande
 * @package app\models
 */
class Commande extends Model {
    public $timestamps = false;
    protected $table = "commandes";
    protected $primaryKey = "id";
    protected $fillable = [
        'total',
        'paid',
        'statut',
        'creationDate'
    ];
    public function paiement(): HasOne {
        return $this->hasOne('\app\models\Paiement');
    }

    public function user(): BelongsTo {
        return $this->belongsTo("\app\models\User");
    }

    public function vinyles(): BelongsToMany {
        return $this->belongsToMany("\app\models\Vinyle");
    }
}