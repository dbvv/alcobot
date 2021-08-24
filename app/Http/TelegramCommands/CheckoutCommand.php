<?php

namespace App\Http\TelegramCommands;

use App\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\UsersCart;
use App\Models\TelegramUsers;

class CheckoutCommand {

    private $telegram;

    private $chatID;

    private $data;

    public function __construct($telegram, $chatID, $data = []) {
        $this->telegram = $telegram;
        $this->chatID = $chatID;
        $this->data = $data;
    }

    public function handle() {
        \Log::info('Creating order');
        if (count($this->data) > 0) {
            extract($this->data);
        }

        $cart = UsersCart::where('telegram_user_id', $this->chatID)->first();

        if (!$cart) {
            Actions::handle($this->telegram, $this->chatID, 'empty_cart');
            return;
        }

        if (!is_array(unserialize($cart->cart))) {
            Actions::handle($this->telegram, $this->chatID, 'empty_cart');
            return;
        }

        if (count(unserialize($cart->cart)) === 0) {
            Actions::handle($this->telegram, $this->chatID, 'empty_cart');
            return;
        }

        $user = TelegramUsers::where('telegram_user_id', $this->chatID)->first();

        if (!$user || !$user->phone) {
            Actions::handle($this->telegram, $this->chatID, 'pre_order');
        }

        $cart_arr = unserialize($cart->cart);

        $total = 0;

        $order_products = "";


        foreach ($cart_arr as $product_id => $count) {
            $product = Product::find($product_id);
            $total += (int) $product->price;
            $order_products .= "{$product->name} ($product->price) X $count\n";
        }

        $order = Order::create([
            'telegram_user_id' => $this->chatID,
            'name' => $user->name,
            'phone' => $user->phone,
            'order_info' => $cart,
            'order_total' => $total,
        ]);

        $order_info = "Заказ №{$order->id}\n Имя: $user->name\n Телефон: $user->phone \n $order_products";

        $managers = explode(',', config('telegram.telegram_manager_id'));
        Cart::clearCart($this->chatID);
        foreach ($managers as $manager) {
            try {
                $this->telegram->sendMessage([
                    'chat_id' => $manager,
                    'text' => $order_info,
                ]);
            } catch (\Exception $e) {

            }
        }

        $this->telegram->sendMessage([
            'chat_id' => $this->chatID,
            'text' => "Заказ успешно создан!\n\n$order_info",
        ]);


        return 0;
    }
}
