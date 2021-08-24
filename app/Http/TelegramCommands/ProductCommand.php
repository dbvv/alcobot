<?php

namespace App\Http\TelegramCommands;

use App\Cart;
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

        $cart = Cart::showCart($this->chatID);

        $cartData = unserialize($cart['cart']->cart);

        $caption = "Просмотр товара в категории: {$product->category->name} \n\n*{$product->name}* \n\n*Цена:* {$product->price} руб. ";
        $msg['parse_mode'] = 'markdown';
        $keyboard = [];

        $productCount = isset($cartData[$product->id]) ? $cartData[$product->id] : 1;
        $productPrice = isset($cartData[$product->id]) ? $product->price * $cartData[$product->id] : $product->price;
        $keyboard[] = [Keyboard::inlineButton([
            'text' => "{$product->price} * $productCount = {$productPrice}",
            'callback_data' => "art:{$product->id}"])
        ];

        $cartActionButtons = [
            Keyboard::inlineButton([
                'text' => '➕',
                'callback_data' => "cart:cart_plus:{$product->id}",
            ]),
            Keyboard::inlineButton([
                'text' => '➖',
                'callback_data' => "cart:cart_minus:{$product->id}",
            ])
        ];

        if (isset($cartData[$product->id])) {
            $cartActionButtons[] = Keyboard::inlineButton([
                'text' => "Удалить",
                "callback_data" => "cart:remove_from_cart:{$product->id}",
            ]);
        }

        $keyboard[] = $cartActionButtons;

        if (!isset($cartData[$product->id])) {
            $keyboard[] = [
                Keyboard::inlineButton([
                    'text' => "Добавить в корзину",
                    'callback_data' => "cart:add_to_cart:{$product->id}",
                ]),
            ];
        }

        $order_action = 'order_create';
        $keyboard[] = [
            Keyboard::inlineButton([
                'text' => 'Добавили? Оформляем заказ?',
                'callback_data' => $order_action,
            ]),
        ];

        $keyboard[] = [
            Keyboard::inlineButton([
                'text' => '... или продолжить покупки?',
                'callback_data' => 'catalog',
            ]),
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
