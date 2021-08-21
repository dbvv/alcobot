<?php

namespace App\Http\TelegramCommands;

use App\Helpers;

class DefaultCommand {

    private $telegram;

    private $chatid;

    private $data;

    public function __construct($telegram, $chatID, $data = []) {
        $this->telegram = $telegram;
        $this->chatID = $chatID;
        $this->data = $data;
    }

    public function handle() {
        if (count($this->data) > 0) {
            extract($this->data);
        }
        $keyboard = config('telegram.keyboard');
        $response = $this->telegram->sendMessage([
            'chat_id' => $this->chatID,
            'text' => 'Выберите нужный раздел меню!',
            'reply_markup' => Helpers::getKeyBoard(),
        ]);
        return 0;
    }
}
