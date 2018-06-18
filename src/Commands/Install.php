<?php

namespace ItsRD\Scaffy\Commands;

use App\Scaffy\Generator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffy:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds scaffy directory for scaffolding';

    /**
     * Create a new command instance.
     *
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
        if(!file_exists(array_first(config('scaffy.scaffy_install')))) {
            File::copyDirectory(realpath(__DIR__ .'/../publishable'), array_first(config('scaffy.scaffy_install')));
            $this->info('Scaffy folder installed in '. array_first(config('scaffy.scaffy_install')));
        } else {
            $this->error('De installatie van de scaffolder folder bestaat al.');
        }
    }
}
