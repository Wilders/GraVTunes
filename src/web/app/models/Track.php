<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Track
 * @package app\models
 */
class Track extends Model {
    public $timestamps = false;
    protected $table = "tracks";
    protected $primaryKey = "id";
    protected $fillable = [
        'nom',
        'description',
        'archived',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo("\app\models\User");
    }

    public function file(): BelongsTo {
        return $this->belongsTo("\app\models\File");
    }

    public function vinyles(): BelongsToMany {
        return $this->belongsToMany("\app\models\Vinyle");
    }

    public function playlists(): BelongsToMany {
        return $this->belongsToMany("\app\models\Playlist");
    }


}