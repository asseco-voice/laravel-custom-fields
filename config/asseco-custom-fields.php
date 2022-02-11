<?php

use Asseco\BlueprintAudit\App\MigrationMethodPicker;
use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\Form;
use Asseco\CustomFields\App\Models\FormTemplate;
use Asseco\CustomFields\App\Models\PlainType;
use Asseco\CustomFields\App\Models\Relation;
use Asseco\CustomFields\App\Models\RemoteType;
use Asseco\CustomFields\App\Models\SelectionType;
use Asseco\CustomFields\App\Models\SelectionValue;
use Asseco\CustomFields\App\Models\Validation;
use Asseco\CustomFields\App\Models\Value;
use Asseco\CustomFields\App\PlainTypes\BooleanType;
use Asseco\CustomFields\App\PlainTypes\DateTimeType;
use Asseco\CustomFields\App\PlainTypes\DateType;
use Asseco\CustomFields\App\PlainTypes\FloatType;
use Asseco\CustomFields\App\PlainTypes\IntegerType;
use Asseco\CustomFields\App\PlainTypes\StringType;
use Asseco\CustomFields\App\PlainTypes\TextType;
use Asseco\CustomFields\App\PlainTypes\TimeType;
use Asseco\CustomFields\App\Traits\Customizable;

return [

    /**
     * Model bindings.
     */
    'models' => [
        'custom_field'    => CustomField::class,
        'form'            => Form::class,
        'form_template'   => FormTemplate::class,
        'plain_type'      => PlainType::class,
        'relation'        => Relation::class,
        'remote_type'     => RemoteType::class,
        'selection_type'  => SelectionType::class,
        'selection_value' => SelectionValue::class,
        'validation'      => Validation::class,
        'value'           => Value::class,
    ],

    'plain_types' => [
        'boolean'  => BooleanType::class,
        'datetime' => DateTimeType::class,
        'date'     => DateType::class,
        'float'    => FloatType::class,
        'integer'  => IntegerType::class,
        'string'   => StringType::class,
        'text'     => TextType::class,
        'time'     => TimeType::class,
    ],

    'migrations'  => [

        /**
         * UUIDs as primary keys.
         */
        'uuid'       => false,

        /**
         * Timestamp types.
         *
         * @see https://github.com/asseco-voice/laravel-common/blob/master/config/asseco-common.php
         */
        'timestamps' => MigrationMethodPicker::PLAIN,

        /**
         * Should the package run the migrations. Set to false if you're publishing
         * and changing default migrations.
         */
        'run'        => true,
    ],

    /**
     * Path to Laravel models in 'path => namespace' format.
     *
     * This does not recurse in folders, so you need to specify
     * an array of paths if non-standard models are to be used
     */
    'models_path' => [
        app_path('Models') => 'App\\Models\\',
    ],

    /**
     * Namespace to Customizable trait.
     */
    'trait_path'  => Customizable::class,
];
