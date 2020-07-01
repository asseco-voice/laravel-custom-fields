<?php

namespace Voice\CustomFields;

use Illuminate\Support\ServiceProvider;
use Voice\Containers\App\Console\Commands\MakeContainers;
use Voice\Containers\CustomMigrationCreator;
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

        $this->registerCreator();
        $this->registerMigrateMakeCommand();

        $this->commands([
            'asseco-voice.command.custom-field-migrate.make'
        ]);
    }

    /**
     * Register the migration creator.
     *
     * @return void
     */
    protected function registerCreator()
    {
        $this->app->singleton('asseco-voice.custom-field-migration.creator', function ($app) {
            return new CustomMigrationCreator($app['files'], __DIR__ . '/stubs');
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateMakeCommand()
    {
        $this->app->singleton('asseco-voice.command.custom-field-migrate.make', function ($app) {
            $creator = $app['asseco-voice.custom-field-migration.creator'];
            $composer = $app['composer'];

            return new MakeCustomFields($creator, $composer);
        });
    }
}
