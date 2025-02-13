<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Users;

class UserMessages extends Model
{
    protected $fillable = [];
    public $table = "arrowchat";
    public $timestamps = false;

    public static $rules = [
        "message" => "required",
    ];

    public function toUser()
    {
        return $this->belongsTo(Users::class, 'to', 'id');
    }

    public function fromUser()
    {
        return $this->belongsTo(Users::class, 'from', 'id');
    }

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public static function insertMessage($message, $fromId, $toId = "")
    {
        self::insert([
            'message' => $message,
            'from' => $fromId,
            'to' => $toId,
            'sent' => now(),
            'read' => 0,
            'user_read' => 0,
            'warup' => 0,
        ]);
    }

    public static function readUserMessages($fromUser, $toUser)
    {
        $results = self::whereNull('read')
            ->where(function ($query) use ($fromUser, $toUser) {
                $query->orWhere('from', $fromUser);
                $query->orWhere('to', $toUser);
            })
            ->get();

        foreach ($results as $result) {
            $result->viewed = now();
            $result->save();
        }
    }

    public static function getInbox()
    {
        return DB::select(
            DB::raw("SELECT * FROM (
                SELECT * FROM (
                    (SELECT `to` as user, message, sent FROM arrowchat WHERE `from` = ? ORDER BY sent DESC)
                    UNION
                    (SELECT `from` as user, message, sent FROM arrowchat WHERE `to` = ? ORDER BY sent DESC)
                ) AS tempTable ORDER BY sent DESC
            ) ordered LEFT JOIN users ON users.id = ordered.user GROUP BY user"),
            [Auth::id(), Auth::id()]
        );
    }
}
