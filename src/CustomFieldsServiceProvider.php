<?php

namespace Voice\CustomFields;

use Illuminate\Support\ServiceProvider;
use Voice\CustomFields\App\Console\Commands\MakeCustomFields;

class CustomFieldsServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/asseco-custom-fields.php', 'asseco-custom-fields');
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');
    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/Config/asseco-custom-fields.php' => config_path('asseco-custom-fields.php'),]);

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
            return new CustomMigrationCreator($app['files'], __DIR__ . '/Stubs');
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
