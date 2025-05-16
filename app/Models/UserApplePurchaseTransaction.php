<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserApplePurchaseTransaction extends Model
{
    use HasFactory;
    protected $table = 'user_apple_purchase_transactions';
    protected $fillable = [
        'ref_user_id',
        'original_transaction_id',
        'transaction_id',
        'transaction_type',
        'expiry_date',
        'is_verified'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
