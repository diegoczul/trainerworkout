<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDeviceInfo extends Model
{
    use HasFactory;
    protected $table = 'user_devices_info';
    protected $fillable = [
        'ref_user_id',
        'device_type',
        'device_brand',
        'device_model',
        'device_unique_id',
    ];
}
