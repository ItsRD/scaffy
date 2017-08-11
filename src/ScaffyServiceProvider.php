<?php

namespace ItsRD\Scaffy;

use Illuminate\Support\ServiceProvider;
use ItsRD\Scaffy\Commands\Install;
use ItsRD\Scaffy\Commands\Scaffolder;

class ScaffyServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish scaffy config
        $this->publishes([
            __DIR__ .'/config/scaffy.php'       => config_path('scaffy.php'),
        ]);

        // Commands
        $this->commands([
            Scaffolder::class,
            Install::class,
        ]);

    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}