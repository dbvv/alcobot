<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\BaseTrait;
use Telegram\Bot\Api;

class CheckUpdates extends Command
{
    use BaseTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $telegram;

    private $chat_id;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        do {
            $updates = $this->getUpdates();

            foreach ($updates as $update) {
                $this->handleUpdate($update);
            }
            sleep(1);
        } while (true);
        return 0;
    }
}
