<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Playlist
 * @package app\models
 */
class Playlist extends Model {
    public $timestamps = false;
    protected $table = "playlists";
    protected $primaryKey = "id";
    protected $fillable = [
        'nom',
        'description',
        'creationDate'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo("\app\models\User");
    }

    public function tracks(): BelongsToMany {
        return $this->belongsToMany("\app\models\Track");
    }
}