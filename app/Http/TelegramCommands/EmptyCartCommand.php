<?php

namespace App\Http\TelegramCommands;

use App\Actions;

class EmptyCartCommand {

    private $telegram;

    private $chatID;

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

        $this->telegram->sendMessage([
            'chat_id' => $this->chatID,
            'text' => 'Ваша корзина пуста',
        ]);

        Actions::handle($this->telegram, $this->chatID, 'catalog');
        return 0;
    }
}
