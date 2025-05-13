<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppstoreNotification extends Model
{
    use HasFactory;

    protected $table = 'appstore_notifications';
    protected $fillable = [
        'original_transaction_id',
        'transaction_id',
        'transaction_status',
        'transaction_env',
        'transaction_type',
        'notification_type',
        'iap_id',
        'price',
        'price_currency',
        'purchase_date',
        'expiry_date',
        'is_auto_renew',
        'transaction_json',
        'is_verified',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
