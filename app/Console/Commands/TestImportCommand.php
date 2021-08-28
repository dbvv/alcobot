<?php

namespace App\Console\Commands;

use App\ImportTrait;
use Illuminate\Console\Command;

class TestImportCommand extends Command
{
    use ImportTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $file = storage_path() . "/app/muster.xlsx";
        $this->importProducts($file);
        return 0;
    }
}
