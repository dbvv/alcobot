<?php

namespace App;

use App\Models\Category;
use App\Models\TelegramUsers;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\FileUpload\InputFile;


class Actions {
    public static function handle($telegram, $chatID, $action = 'default', $data = []) {
        $commandClass = config("telegram.commands.$action");
        $command = new $commandClass($telegram, $chatID, $data);
        $command->handle();
    }

    public static function saveContact($contact, $chatID) {
        if (isset($contact['phone_number'])) {
            $user = TelegramUsers::where('telegram_user_id', $chatID)->first();

            if ($user) {
                $user->phone = $contact['phone_number'];
                $user->save();
            } else {
                $user = TelegramUsers::create([
                    'telegram_user_id' => $chatID,
                    'first_name' => $contact['first_name'],
                    'phone' => $contact['phone_number'],
                ]);
            }

            return $user;
        }
    }
}
