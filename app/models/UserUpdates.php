<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Users;
use Workouts;

class UserUpdates extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    /**
     * Get the user associated with the update.
     */
    public function user()
    {
        return $this->hasOne(Users::class, 'userId', 'id');
    }

    /**
     * Get the trainers associated with the update.
     */
    public function trainer()
    {
        return $this->hasMany(Users::class, 'trainerId', 'id');
    }

    /**
     * Get the groups associated with the update.
     */
    public function group()
    {
        return $this->hasMany(UserGroups::class, 'teamId', 'id');
    }

    /**
     * Get the workouts associated with the update.
     */
    public function workout()
    {
        return $this->hasMany(Workouts::class, 'auxId', 'id')->where('type', 'workout');
    }
}
