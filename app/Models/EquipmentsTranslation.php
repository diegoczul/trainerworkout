<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentsTranslation extends Model
{
    protected $fillable = ['name', 'nameEngine'];

    public $timestamps = false;
}
