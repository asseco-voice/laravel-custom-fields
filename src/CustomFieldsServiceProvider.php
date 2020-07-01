<?php

namespace Voice\CustomFields;

use Illuminate\Support\ServiceProvider;
use Voice\CustomFields\App\Console\Commands\MakeCustomFields;

class CustomFieldsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/config/asseco-voice.php' => config_path('asseco-voice.php'),]);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/asseco-voice.php', 'asseco-voice');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');

        $this->commands([
            'asseco-voice.command.migrate.make',
        ]);
    }
}
