<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Illuminate\Console\GeneratorCommand;

class TelegramBotCommandGenerator extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:tgcommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create custom command for telegram bot';

    protected $type = 'class';

    /**
     * Class name to save
     */
    protected $className;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = $this->getPath($name);

        $filePath = $this->getPath("Http/TelegramCommands/{$name}Command");
        $folderPath = str_replace('.php', '', $this->getPath("Http/TelegramCommands"));

        if (file_exists($filePath)) {
            $this->info('Command already exists!');
            return;
        }

        $c = $this->buildClass($name);

        $this->files->put($filePath, $c);

        $this->info('Command created!');

        //dump($this->files);
        return 0;
    }

    public function replaceClass($stub, $name) {
        $name = $this->argument('name');
        if (!$this->argument('name')) {
            throw new InvalidArgumentException("Missing required name argument");
        }
        $stub = parent::replaceClass($stub, $name);
        return str_replace('Dummy', $name, $stub);
    }

    protected function getStub() {
        return storage_path() . '/app/TGCommand.stub';
    }

    protected function getDefaultNamespace($rootNamespace) {
        return 'App\Http\TelegramCommands';
    }
}
