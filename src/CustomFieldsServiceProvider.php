<?php

declare(strict_types=1);

namespace Asseco\CustomFields;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\Form;
use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\Contracts\Relation;
use Asseco\CustomFields\App\Contracts\RemoteType;
use Asseco\CustomFields\App\Contracts\SelectionType;
use Asseco\CustomFields\App\Contracts\SelectionValue;
use Asseco\CustomFields\App\Contracts\Validation;
use Asseco\CustomFields\App\Contracts\Value;
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

        if (config('asseco-custom-fields.migrations.run')) {
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
        $this->app->bind(CustomField::class, config('asseco-custom-fields.models.custom_field'));
        $this->app->bind(Form::class, config('asseco-custom-fields.models.form'));
        $this->app->bind(PlainType::class, config('asseco-custom-fields.models.plain_type'));
        $this->app->bind(RemoteType::class, config('asseco-custom-fields.models.remote_type'));
        $this->app->bind(SelectionType::class, config('asseco-custom-fields.models.selection_type'));
        $this->app->bind(SelectionValue::class, config('asseco-custom-fields.models.selection_value'));
        $this->app->bind(Relation::class, config('asseco-custom-fields.models.relation'));
        $this->app->bind(Validation::class, config('asseco-custom-fields.models.validation'));
        $this->app->bind(Value::class, config('asseco-custom-fields.models.value'));
    }
}
