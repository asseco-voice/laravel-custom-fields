<?php

declare(strict_types=1);

namespace Asseco\CustomFields;

use Illuminate\Support\ServiceProvider;

class CustomFieldsServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/asseco-custom-fields.php', 'asseco-custom-fields');
    }

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->bindClasses();

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        if (config('asseco-custom-fields.runs_migrations')) {
            $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        }

        $this->publishes([
            __DIR__ . '/../migrations' => database_path('migrations'),
        ], 'asseco-custom-fields');

        $this->publishes([
            __DIR__ . '/../config/asseco-custom-fields.php' => config_path('asseco-custom-fields.php'),
        ], 'asseco-custom-fields');
    }

    protected function bindClasses()
    {
        $this->app->bind('cf-custom-field', config('asseco-custom-fields.custom_field_model'));
        $this->app->bind('cf-form', config('asseco-custom-fields.form_model'));
        $this->app->bind('cf-plain-type', config('asseco-custom-fields.plain_type_model'));
        $this->app->bind('cf-remote-type', config('asseco-custom-fields.remote_type_model'));
        $this->app->bind('cf-selection-type', config('asseco-custom-fields.selection_type_model'));
        $this->app->bind('cf-selection-value', config('asseco-custom-fields.selection_value_model'));
        $this->app->bind('cf-relation', config('asseco-custom-fields.relation_model'));
        $this->app->bind('cf-validation', config('asseco-custom-fields.validation_model'));
        $this->app->bind('cf-value', config('asseco-custom-fields.value_model'));
    }
}
