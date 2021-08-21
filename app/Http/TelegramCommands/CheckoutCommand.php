<?php

namespace App\Http\TelegramCommands;

use App\Models\Product;
use App\Models\Order;
use App\Models\UsersCart;

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
        if (count($this->data) > 0) {
            extract($this->data);
        }

        $cart_arr = unserialize($cart);

        $total = 0;

        $order_products = "";


        foreach ($cart_arr as $product_id => $count) {
            $product = Product::find($product_id);
            $total += (int) $product->price;
            $order_products .= "{$product->name} ($product->price) X $count\n";
        }

        $order = Order::create([
            'telegram_user_id' => $this->chatID,
            'name' => $name,
            'phone' => $phone,
            'order_info' => $cart,
            'order_total' => $total,
        ]);

        $order_info = "Заказ №{$order->id}\n Имя: $name\n Телефон: $phone \n $order_products";

        $managers = explode(',', config('telegram.telegram_manager_id'));

        foreach ($managers as $manager) {
            $this->telegram->sendMessage([
                'chat_id' => $manager,
                'text' => $order_info,
            ]);
        }

        return 0;
    }
}
