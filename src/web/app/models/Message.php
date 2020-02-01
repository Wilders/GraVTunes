<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Message
 * @package app\models
 */
class Message extends Model {
    public $timestamps = false;
    protected $table = "messages";
    protected $primaryKey = "id";

    public function ticket(): BelongsTo {
        return $this->belongsTo("\app\models\Ticket");
    }
}