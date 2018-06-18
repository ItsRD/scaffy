<?php

namespace ItsRD\Scaffy\Commands;

use ItsRD\Scaffy\Generator;
use Illuminate\Console\Command;

class Scaffolder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffy:scaffold {name} {--template=default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make your life easier by scaffolding your files';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $generator = new Generator();
        // Check if scaffy folder exists
        if (! $generator->checkIfInstalled()) {
            return $this->error('Scaffy isn\'t installed yet, please run php artisan scaffy:install');
        }
        // Execute generator with given parameters (name & template)
        $generator->execute($this->argument('name'), $this->option('template'));

        // check if any errors return
        if ($generator->hasErrors()) {
            foreach ($generator->getErrors() as $error) {
                $this->error($error);
            }
        } else {
            foreach ($generator->getMessages() as $message) {
                $this->info($message);
            }
            $this->info('Finished.');
        }
    }
}
