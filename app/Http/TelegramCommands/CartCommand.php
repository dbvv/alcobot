<?php

namespace App\Http\TelegramCommands;

use App\Cart;
use App\Models\TelegramUsers;
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


        if (!isset($this->data['action'])) {
            return;
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
            $telegramUser = TelegramUsers::where('telegram_user_id', $this->chatID)->first();

            $order_action = 'order_create';
            if ($telegramUser == null && !$telegramUser->phone) {
                $order_action = 'pre_order';
            }
            if (count(unserialize($cart['cart']->cart)) > 0) {
                $keyboard = Keyboard::make([
                    'inline_keyboard' => [
                        [
                            Keyboard::inlineButton([
                                'text' => 'Редактировать',
                                'callback_data' => 'cart:edit_cart',
                            ]),
                            Keyboard::inlineButton([
                                'text' => 'Оформить заказ',
                                'callback_data' => $order_action,
                            ]),
                        ],
                    ],
                ]);
            } else {
                $keyboard = null;
            }
            $response = $this->telegram->sendMessage([
                'chat_id' => $this->chatID,
                'text' => $cart['txt'],
                'reply_markup' => $keyboard,
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
            break;
        }
        return 0;
    }
}
