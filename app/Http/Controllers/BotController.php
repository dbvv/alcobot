<?php

namespace App\Http\Controllers;

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
        $msg = [
            'chat_id' => $chat_id,
            'text' => '',
        ];
        if (strpos($callback['callback_query']['data'], 'category') !== false) {
            $category_id = (int) str_replace('category:', '', $callback['callback_query']['data']);
            $category = Category::find($category_id);
            $products = Product::where('category_id', $category_id)->get();

            if (count($products) > 0) {
                $msg['text'] = "Категория: {$category->name}";
                $keyboard = [];

                foreach ($products as $product) {
                    $keyboard[] = [Keyboard::inlineButton([
                        'text' => "$product->price р. - $product->name",
                        'callback_data' => "product:{$product->id}",
                    ])];
                }

                $msg['reply_markup'] = Keyboard::make([
                    'inline_keyboard' => $keyboard,
                ]);
            } else {
                $msg['text'] = 'В категории нет товаров';
            }
            $reponse = $this->telegram->sendMessage($msg);
        } elseif (strpos($callback['callback_query']['data'], 'product') !== false) {
            $product_id = (int) str_replace('product:', '', $callback['callback_query']['data']);
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
        }
        \Log::info($msg);
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
