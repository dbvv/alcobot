<?php

namespace App;

use App\Models\Category;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\FileUpload\InputFile;


class Actions {
    public static function handle($telegram, $chatID, $action = 'default', $data = []) {
        $commandClass = config("telegram.commands.$action");
        $command = new $commandClass($telegram, $chatID, $data);
        $command->handle();
    }
}
