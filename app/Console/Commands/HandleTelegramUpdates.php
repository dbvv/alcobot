<?php

namespace App\Console\Commands;

use App\BaseTrait;
use Illuminate\Console\Command;
use Telegram\Bot\Api;

class HandleTelegramUpdates extends Command
{
    use BaseTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tg:handle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle telegram updates';

    private $telegram;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $key = config('telegram.api_key');

        if ($key) {
            try {
                $this->telegram = new Api($key);
            } catch (\Exception $e) {
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
        $updates = $this->getUpdates();

        foreach ($updates as $update) {
            $this->handleUpdate($update);
        }
        return 0;
    }
}
