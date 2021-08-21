<?php

namespace App;

use Telegram\Bot\Keyboard\Keyboard;

class Helpers {
    public static function getKeyBoard() {
        $keyboard = config('telegram.keyboard');
        return Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
        ]);
    }
}
