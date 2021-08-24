<?php

namespace App\Http\TelegramCommands;

use App\Cart;
use App\Models\UsersCart;
use App\Models\TelegramUsers;
use Telegram\Bot\Keyboard\Keyboard;

class PreOrderCommand {

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

        $cart = Cart::showCart($this->chatID);

        if (!$cart) {
            return;
        }

        $telegramUser = TelegramUsers::where('telegram_user_id', $this->chatID)->first();

        if ($telegramUser && $telegramUser->phone) {
            $response = $this->telegram->sendMessage([
                'chat_id' => $this->chatID,
                'text' => "Ваш номер $telegramUser->phone",
                'reply_markup' => Keyboard::make([
                    'keyboard' => [
                        [
                            Keyboard::button([
                                'text' => 'Создать заказ',
                                'callback_data' => 'order_create',
                            ]),
                        ]
                    ],
                ]),
            ]);
        } else {
            $response = $this->telegram->sendMessage([
                'chat_id' => $this->chatID,
                'text' => $cart['txt'],
            ]);
            $response = $this->telegram->sendMessage([
                'chat_id' => $this->chatID,
                'text' => 'Отправьте Ваш номер телефона',
                'reply_markup' => Keyboard::make([
                    'keyboard' => [
                        [
                            Keyboard::button([
                                'text' => "Отправьте ваш номер телефона",
                                'request_contact' => true,
                            ]),
                            Keyboard::button([
                                'text' => 'Вернуться в главное меню',
                                'callback_data' => 'default',
                            ]),
                        ]
                    ],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ]),
            ]);
        }

        return 0;
    }
}
