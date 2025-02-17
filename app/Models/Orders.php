<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orders extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'userId',
        'total',
        'subtotal',
        'street',
        'city',
        'province',
        'country',
        'postalcode',
        'orderDate',
        'paidBy',
        'status',
        'currency',
    ];
    protected $dates = ['deleted_at'];
}
