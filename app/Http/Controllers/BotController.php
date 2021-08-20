<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\LastConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;

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

    private function getKeyboard() {
        $keyboard = config('telegram.keyboard');

        return Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
        ]);
    }

    private function welcomeMessage($chat_id) {
        $keyboard = config('telegram.keyboard');

        $response = $this->telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Выберите нужный раздел меню!',
            'reply_markup' => $this->getKeyBoard(),
        ]);

        $messageId = $response->getMessageId();
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

    private function handleUpdate($update) {
        if (isset($update['message'])) {
            $chat_id = $update['message']['from']['id'];
            $message = $update['message']['text'];
            $this->handleMessage($message, $chat_id);
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
                $keyboard = [];
                $categories = Category::all();
                $k = 0;
                $row = [];
                foreach ($categories as $category) {
                    $row[] = Keyboard::inlineButton([
                        'text' => $category->name,
                        'callback_data' => "category:{$category->id}",
                    ]);

                    $k++;

                    if ($k >= 3) {
                        $k = 0;
                        $keyboard[] = $row;
                        $row = [];
                    }
                }
                $response = $this->telegram->sendMessage([
                    'chat_id' => $chat_id,
                    'text' => 'Выберите категорию',
                    'reply_markup' => Keyboard::make([
                        'inline_keyboard' => $keyboard,
                        //'one_time_keyboard' => true,
                    ]),
                ]);
                break;
            default:
                $this->welcomeMessage($chat_id);
                break;
        }
    }
}
