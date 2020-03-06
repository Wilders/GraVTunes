<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class File
 * @package app\models
 */
class File extends Model {
    public $timestamps = false;
    protected $table = "files";
    protected $primaryKey = "id";
    protected $fillable = [
        'path',
        'hash',
        'duree',
        'size'
    ];
    public function tracks(): HasMany {
        return $this->hasMany("\app\models\Track");
    }
}