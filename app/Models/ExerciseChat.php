<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseChat extends Model
{
    protected $table = 'exercise_chats';

    protected $fillable = [
        'user_id',
        'exercise_id',
        'message',
        'sender'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercises::class, 'exercise_id');
    }
}
