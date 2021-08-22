<?php

namespace App\Http\Controllers;

use App\Actions;
use App\Models\Category;
use App\Models\Product;
use App\Models\LastConversation;
use App\Models\UsersMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\FileUpload\InputFile;

class BotController extends Controller
{
    private $telegram;

    private $chat_id;

    public function __construct() {
        $key = config('telegram.api_key');
        $name = config('telegram.user_name');
        $this->chat_id = 1050626474;

        if ($key) {
            try {
                $this->telegram = new Api($key);
            } catch (\Exception $e) {
                dump($e);
                Log::error($e);
            }
        }
    }

    public function webhook(Request $request) {

    }



    private function getUpdates() {
        $updates = $this->telegram->getUpdates();
        $last = LastConversation::first();
        $u = [];
        foreach ($updates as $update) {
            if (($last && $update['update_id'] > (int) $last->last_conversation) || !$last) {
                $u[$update['update_id']] = $update;
            }
        }
        return $u;
    }

    private function saveUpdate($update) {
        UsersMessage::create([
            'user_id' => $update['message']['from']['id'],
            'update_id' => $update['update_id'],
            'message' => serialize($update),
        ]);
    }

    private function handleUpdate($update) {
        if (isset($update['message'])) {
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
        if (strpos($callback['callback_query']['data'], 'category') !== false) {
            $category_id = (int) str_replace('category:', '', $callback['callback_query']['data']);
            Actions::handle($this->telegram, $chat_id, 'category', compact('category_id'));
        } elseif (strpos($callback['callback_query']['data'], 'product') !== false) {
            $product_id = (int) str_replace('product:', '', $callback['callback_query']['data']);
            Actions::handle($this->telegram, $chat_id, 'product', compact('product_id'));
        } elseif (strpos($callback['callback_query']['data'], 'cart') !== false) {
            $callback_query_data = explode(':', $callback['callback_query']['data']);

            $data = [];

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
        }
    }

    public function updates(Request $request) {
        $updates = $this->getUpdates();

        foreach ($updates as $update) {
            $this->handleUpdate($update);
        }
        dump($updates);
    }

    private function handleMessage($message, $chat_id) {
        switch ($message) {
            case 'Каталог':
                Actions::handle($this->telegram, $chat_id, 'catalog');
                break;
            default:
                break;
        }
    }
}
