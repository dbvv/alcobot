<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'telegram_user_id',
        'name',
        'phone',
        'address',
        'order_info', // serialized array of arrays [ ['product' => 1, count => 1, 'price' => 120] ]
        'order_total',
    ];
}
