<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUsers extends Model
{
    use HasFactory;

    protected $fillable = [
        'telegram_user_id',
        'lang',
        'first_name',
        'last_name',
        'user_name',
        'phone',
    ];
}
