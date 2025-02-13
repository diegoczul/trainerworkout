<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodyGroupsTranslation extends Model
{
    protected $table = 'bodygroups_translations';
    protected $fillable = ['name', 'description'];

    public $timestamps = false;
}
