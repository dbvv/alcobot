<?php

namespace App;

use App\Models\LastConversation;
use App\Models\UsersMessage;

trait BaseTrait {
    /**
     * Parse telegrram updates
     */
    public function getUpdates() {
        $updates = $this->telegram->getUpdates();
        //dd($updates);
        $last = LastConversation::first();
        $u = [];
        foreach ($updates as $update) {
            if (($last && $update['update_id'] > (int) $last->last_conversation) || !$last) {
                $u[$update['update_id']] = $update;
            }
        }
        return $u;
    }

    public function handleMessage($message, $chat_id) {
        switch ($message) {
            case 'Каталог':
                Actions::handle($this->telegram, $chat_id, 'catalog');
                break;
            case 'Корзина':
                Actions::handle($this->telegram, $chat_id, 'cart', ['action' => 'show_cart']);
                break;
            case '/start':
                Actions::handle($this->telegram, $chat_id, 'default');
                break;
            default:
                Actions::handle($this->telegram, $chat_id, 'default');
                break;
        }
    }

    /**
     * Save users update
     */
    private function saveUpdate($update) {
        UsersMessage::create([
            'user_id' => $update['message']['from']['id'],
            'update_id' => $update['update_id'],
            'message' => serialize($update),
        ]);
    }

    private function handleUpdate($update) {
        if (isset($update['message']) && isset($update['message']['contact'])) {
            $chat_id = $update['message']['from']['id'];
            $user = Actions::saveContact($update['message']['contact'], $update['message']['from']['id']);
            $cart = Cart::showCart($user->telegram_uset_id);
            $data = [
                'cart' => $cart->cart,
                'name' => $user->first_name,
                'phone' => $user->phone,
            ];
            Actions::handle($this->telegram, $chat_id, 'order_create', $data);

        } elseif (isset($update['message'])) {
            $chat_id = $update['message']['from']['id'];
            $message = $update['message']['text'];
            $this->handleMessage($message, $chat_id);
            $this->saveUpdate($update);
        }

        if (isset($update['callback_query'])) {
            $this->handleCallback($update);
        }

        $last = LastConversation::first();
        if (!$last) {
            $last = LastConversation::create([
                'last_conversation' => $update['update_id'],
            ]);
        } else {
            $last->last_conversation = $update['update_id'];
            $last->save();
        }
    }

    private function handleCallback($callback) {
        $chat_id = $callback['callback_query']['from']['id'];
        \Log::info($callback['callback_query']['data']);
        if (strpos($callback['callback_query']['data'], 'category') !== false) {
            $category_id = (int) str_replace('category:', '', $callback['callback_query']['data']);
            Actions::handle($this->telegram, $chat_id, 'category', compact('category_id'));
        } elseif (strpos($callback['callback_query']['data'], 'product') !== false) {
            $product_id = (int) str_replace('product:', '', $callback['callback_query']['data']);
            Actions::handle($this->telegram, $chat_id, 'product', compact('product_id'));
        } elseif (strpos($callback['callback_query']['data'], 'cart') !== false) {
            $callback_query_data = explode(':', $callback['callback_query']['data']);
            $data = [];
            //dd($callback_query_data);
            // cart product actions
            if (count($callback_query_data) === 3) {
                $data['action'] = $callback_query_data[1];
                $data['product_id'] = (int) $callback_query_data[2];
            }
            // show cart
            if (count($callback_query_data) === 2) {
                $data['action'] = $callback_query_data[1];
            }
            Actions::handle($this->telegram, $chat_id, 'cart', $data);
        } elseif ($callback['callback_query']['data'] === 'catalog') {
            Actions::handle($this->telegram, $chat_id, 'catalog');
        } elseif ($callback['callback_query']['data'] === 'pre_order') {
            Actions::handle($this->telegram, $chat_id, 'pre_order');
        } elseif ($callback['callback_query']['data'] === 'order_create') {
            Actions::handle($this->telegram, $chat_id, 'order_create');
        }
    }
}
