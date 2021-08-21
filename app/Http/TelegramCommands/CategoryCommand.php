<?php

namespace App\Http\TelegramCommands;

use App\Models\Category;
use App\Models\Product;
use Telegram\Bot\Keyboard\Keyboard;

class CategoryCommand {

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
        $category = Category::find($category_id);
        $products = Product::where('category_id', $category_id)->get();
        $msg = [
            'chat_id' => $this->chatID,
            'text' => '',
        ];
        if (count($products) > 0) {
            $msg['text'] = "Категория: {$category->name}";
            $keyboard = [];

            foreach ($products as $product) {
                $keyboard[] = [Keyboard::inlineButton([
                    'text' => "$product->price р. - $product->name",
                    'callback_data' => "product:{$product->id}",
                ])];
            }

            $msg['reply_markup'] = Keyboard::make([
                'inline_keyboard' => $keyboard,
            ]);
        } else {
            $msg['text'] = 'В категории нет товаров';
        }
        $reponse = $this->telegram->sendMessage($msg);
        return 0;
    }
}
