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
use App\BaseTrait;

class BotController extends Controller
{
    use BaseTrait;

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

    public function updates(Request $request) {
        $updates = $this->getUpdates();

        foreach ($updates as $update) {
            $this->handleUpdate($update);
        }
        dump($updates);
    }
}
