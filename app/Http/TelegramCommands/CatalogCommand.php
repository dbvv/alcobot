<?php

namespace App\Http\TelegramCommands;

use App\Models\Category;
use Telegram\Bot\Keyboard\Keyboard;

class CatalogCommand {

    private $telegram;

    private $chatID;

    private $data;

    public function __construct($telegram, $chatID, $data = []) {
        $this->telegram = $telegram;
        $this->chatID = $chatID;
        $this->data = $data;
    }

    public function handle() {
        $keyboard = [];
        $categories = Category::all();
        $k = 0;
        $row = [];
        foreach ($categories as $category) {
            $row[] = Keyboard::inlineButton([
                'text' => $category->name,
                'callback_data' => "category:{$category->id}",
            ]);
            $k++;
            if ($k >= 3) {
                $k = 0;
                $keyboard[] = $row;
                $row = [];
            }
        }
        $response = $this->telegram->sendMessage([
            'chat_id' => $this->chatID,
            'text' => 'Выберите категорию',
            'reply_markup' => Keyboard::make([
                'inline_keyboard' => $keyboard,
            ]),
        ]);
        return 0;
    }
}
