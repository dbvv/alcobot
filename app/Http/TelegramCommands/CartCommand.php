<?php

namespace App\Http\TelegramCommands;

use App\Cart;
use Telegram\Bot\Keyboard\Keyboard;

/**
 * Product cart manipulations
 */
class CartCommand {

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

        switch ($action) {
        case 'add_to_cart':
            Cart::addToCart($this->chatID, $product_id);
            break;
        case 'remove_from_cart':
            Cart::removeFromCart($this->chatID, $product_id);
            break;
        case 'cart_plus':
            Cart::increaseInCart($this->chatID, $product_id);
            break;
        case 'cart_minus':
            Cart::decreaseInCart($this->chatID, $product_id);
            break;
        case 'show_cart':
            $cart = Cart::showCart($this->chatID);
            $response = $this->telegram->sendMessage([
                'chat_id' => $this->chatID,
                'text' => $cart['txt'],
                'reply_markup' => Keyboard::make([
                    'inline_keyboard' => [
                        [
                            Keyboard::inlineButton([
                                'text' => 'Редактировать',
                                'callback_data' => 'edit_cart',
                            ]),
                            Keyboard::inlineButton([
                                'text' => 'Офромить заказ',
                                'callback_data' => 'order_Create',
                            ]),
                        ],
                    ],
                ]),
            ]);
            break;
        case 'edit_cart':
            $cart = Cart::showCart($this->chatID, true);
            $keyboard = [];
            $cartData = unserialize($cart['cart']->cart);
            foreach ($cartData as $productID => $count) {
                $product = $cart['cartProducts'][$productID];
                $keyboard[] = [
                    Keyboard::inlineButton([
                        'text' => "{$product->name} ($count)",
                        'callback_data' => "product:$productID",
                    ]),
                ];
            }
            $keyboard[] = [
                Keyboard::inlineButton([
                    'text' => 'Оставляем как есть?',
                    'callback_data' => 'cart',
                ]),
            ];
            $this->telegram->sendMessage([
                'chat_id' => $this->chatID,
                'text' => 'Выберите товар, который нужно изменить или отредактировать',
                'reply_markup' => Keyboard::make([
                    'inline_keyboard' => $keyboard,
                ]),
            ]);
        }
        return 0;
    }
}
