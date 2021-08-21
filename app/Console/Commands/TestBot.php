<?php

namespace App\Console\Commands;

use App\Actions;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Console\Command;
use Telegram\Bot\Api;

class TestBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    protected $url = 'http://f0539356.xsph.ru/';

    private $client;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $chat_id;

    private $telegram;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->url,
        ]);
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

    private function testCommand() {
        $action = 'order_create';

        $data = [
            'cart' => serialize([
                1 => 4,
            ]),
            'name' => 'Test',
            'phone' => '+711111111111',
        ];

        Actions::handle($this->telegram, $this->chat_id, $action, $data);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->testCommand();
        //$response = $this->client->request('GET', 'tg/updates');
        //dump($response);

        return 0;
    }
}
