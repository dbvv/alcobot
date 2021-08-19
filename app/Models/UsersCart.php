<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'telegram_user_id',
        'cart', // serialized array - product id => count,
    ];
}
