<?php

declare(strict_types=1);

namespace Asseco\CustomFields;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\Form;
use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\Contracts\PlainTypes\BooleanType;
use Asseco\CustomFields\App\Contracts\PlainTypes\DateTimeType;
use Asseco\CustomFields\App\Contracts\PlainTypes\DateType;
use Asseco\CustomFields\App\Contracts\PlainTypes\FloatType;
use Asseco\CustomFields\App\Contracts\PlainTypes\IntegerType;
use Asseco\CustomFields\App\Contracts\PlainTypes\StringType;
use Asseco\CustomFields\App\Contracts\PlainTypes\TextType;
use Asseco\CustomFields\App\Contracts\PlainTypes\TimeType;
use Asseco\CustomFields\App\Contracts\Relation;
use Asseco\CustomFields\App\Contracts\RemoteType;
use Asseco\CustomFields\App\Contracts\SelectionType;
use Asseco\CustomFields\App\Contracts\SelectionValue;
use Asseco\CustomFields\App\Contracts\Validation;
use Asseco\CustomFields\App\Contracts\Value;
use Illuminate\Support\Facades\Route;
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

        $this->routeModelBinding();
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

        $this->app->bind(BooleanType::class, config('asseco-custom-fields.plain_types.boolean'));
        $this->app->bind(DateTimeType::class, config('asseco-custom-fields.plain_types.date_time'));
        $this->app->bind(DateType::class, config('asseco-custom-fields.plain_types.date'));
        $this->app->bind(FloatType::class, config('asseco-custom-fields.plain_types.float'));
        $this->app->bind(IntegerType::class, config('asseco-custom-fields.plain_types.integer'));
        $this->app->bind(StringType::class, config('asseco-custom-fields.plain_types.string'));
        $this->app->bind(TextType::class, config('asseco-custom-fields.plain_types.text'));
        $this->app->bind(TimeType::class, config('asseco-custom-fields.plain_types.time'));
    }

    protected function routeModelBinding()
    {
        Route::model('custom_field', get_class(app(CustomField::class)));
        Route::model('form', get_class(app(Form::class)));
        Route::model('remote_type', get_class(app(RemoteType::class)));
        // Plain type pattern is defined in routes, so no need to register it here
        // Route::model('plain_type', get_class(app(PlainType::class)));
        Route::model('selection_type', get_class(app(SelectionType::class)));
        Route::model('selection_value', get_class(app(SelectionValue::class)));
        Route::model('relation', get_class(app(Relation::class)));
        Route::model('validation', get_class(app(Validation::class)));
        Route::model('value', get_class(app(Value::class)));
    }
}
