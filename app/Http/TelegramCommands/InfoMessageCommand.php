<?php

namespace App\Http\TelegramCommands;

use App\Models\Page;

class InfoMessageCommand {

    private $telegram;

    private $chatID;

    private $data;

    public function __construct($telegram, $chatID, $data = []) {
        $this->telegram = $telegram;
        $this->chatID = $chatID;
        $this->data = $data;
    }

    public function handle() {
        if (!isset($this->data['page'])) {
            return;
        }

        if (count($this->data) > 0) {
            extract($this->data);
        }

        $this->telegram->sendMessage([
            'chat_id' => $this->chatID,
            'text' => $page->content,
        ]);
        return 0;
    }
}
