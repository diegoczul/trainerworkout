<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSetsHistory extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'user_sets_history';
    protected $fillable = [
        'id',
        'ref_user_id',
        'ref_workout_id',
        'ref_exercise_id',
        'ref_set_id',
        'weight',
    ];
}
