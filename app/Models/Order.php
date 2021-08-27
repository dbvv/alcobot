<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TelegramUsers as TelegramUser;

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

    public function telegram_user() {
        return $this->belongsTo(TelegramUser::class, 'telegram_user_id', 'telegram_user_id');
    }

    public function userData() {
        $uID = $this->telegram_user_id;
        $telegramUser = TelegramUser::where('telegram_user_id', $uID)->first();

        if (!$telegramUser) {
            return '-';
        } else {
            $name = '';
            if ($telegramUser->first_name) {
                $name = "$telegramUser->first_name ($telegramUser->phone)";
            } else {
                $name = $telegramUser->phone;
            }
            return $name;

        }
    }
}
