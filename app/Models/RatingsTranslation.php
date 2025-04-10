<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingsTranslation extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
