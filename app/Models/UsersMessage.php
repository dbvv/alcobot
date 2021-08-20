<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'update_id',
        'message',
        'user_id',
    ];
}
