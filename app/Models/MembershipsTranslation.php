<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipsTranslation extends Model
{
    protected $fillable = ['name', 'description', 'features'];
    public $timestamps = false;
}
