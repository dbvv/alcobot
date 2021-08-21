<?php

namespace App\Http\TelegramCommands;

use App\Models\Product;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Keyboard\Keyboard;

/**
 * Show product output with all data
 */
class ProductCommand {

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
        $msg = [
            'chat_id' => $this->chatID,
            'text' => '',
        ];
        $product = Product::where('id', $product_id)->with('category')->first();

        $caption = " Просмотр товара в категории: {$product->category->name} \n\n*{$product->name}* \n\n*Цена:* {$product->price} руб. ";
        $msg['parse_mode'] = 'markdown';

        $keyboard = [
            [Keyboard::inlineButton([
                'text' => "{$product->price} * 1 = {$product->price}", // TODO update after cart
                'callback_data' => "cart:{$product->id}"])
            ],
            [
                Keyboard::inlineButton([
                    'text' => '➕',
                    'callback_data' => "add_to_cart:{$product->id}",
                ]),
                Keyboard::inlineButton([
                    'text' => '➖',
                    'callback_data' => "remove_from_cart:{$product->id}",
                ])
            ],
            [
                Keyboard::inlineButton([
                    'text' => "Добавить в корзину",
                    'callback_data' => "add_to_cart:{$product->id}",
                ]),
            ],
            [
                Keyboard::inlineButton([
                    'text' => 'Добавили? Оформляем заказ?',
                    'callback_data' => 'order_create',
                ]),
            ],
            [
                Keyboard::inlineButton([
                    'text' => '... или продолжить покупки?',
                    'callback_data' => 'catalog',
                ]),
            ],
        ];
        $msg['reply_markup'] = Keyboard::make([
            'inline_keyboard' => $keyboard,
        ]) ;

        if ($product->image) {
            $msg['caption'] = $caption;
            $photo = InputFile::create(public_path() . "/$product->image", $product->name);
            $msg['photo'] = $photo;
            $response = $this->telegram->sendPhoto($msg);
        } else {
            $msg['text'] = $caption;
            $reponse = $this->telegram->sendMessage($msg);
        }
        return 0;
    }
}
