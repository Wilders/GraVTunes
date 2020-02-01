<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Ticket
 * @package app\models
 */
class Ticket extends Model {
    public $timestamps = false;
    protected $table = "tickets";
    protected $primaryKey = "id";

    public function messages(): HasMany {
        return $this->hasMany("\app\models\Message");
    }

    public function user(): BelongsTo {
        return $this->belongsTo("\app\models\User");
    }
}