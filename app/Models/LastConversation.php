<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LastConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_conversation',
    ];
}
