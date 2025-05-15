<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItems extends Model
{
    use SoftDeletes;

    protected $table = 'order_items';
    protected $fillable = [
        'orderId',
        'itemId',
        'itemType',
        'quantity',
        'price',
        'paid',
        'apple_transaction_id',
        'apple_original_transaction_id',
    ];
    protected $dates = ['deleted_at'];
}
