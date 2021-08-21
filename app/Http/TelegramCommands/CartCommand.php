<?php

namespace App\Http\TelegramCommands;

use App\Cart;

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
            Cart::addToCart($chat_id, $product_id);
            break;
        case 'remove_from_cart':
            Cart::removeFromCart($chat_id, $product_id);
            break;
        case 'cart_plus':
            Cart::increaseInCart($chat_id, $product_id);
            break;
        case 'cart_minus':
            Cart::decreaseInCart($chat_id, $product_id);
            break;
        }
        return 0;
    }
}
