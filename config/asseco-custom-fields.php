<?php

use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\Form;
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

    'custom_field_model'    => CustomField::class,
    'form_model'            => Form::class,
    'plain_type_model'      => PlainType::class,
    'remote_type_model'     => RemoteType::class,
    'selection_type_model'  => SelectionType::class,
    'selection_value_model' => SelectionValue::class,
    'relation_model'        => Relation::class,
    'validation_model'      => Validation::class,
    'value_model'           => Value::class,

    /**
     * Should the package run the migrations. Set to false if you're publishing
     * and changing default migrations.
     */
    'runs_migrations'       => true,

    /**
     * Path to Laravel models in 'path => namespace' format.
     *
     * This does not recurse in folders, so you need to specify
     * an array of paths if non-standard models are to be used
     */
    'models_path'           => [
        app_path('Models') => 'App\\Models\\',
    ],

    /**
     * Namespace to Customizable trait.
     */
    'trait_path'            => Customizable::class,

    'type_mappings' => [
        'plain'     => [
            'string'   => StringType::class,
            'integer'  => IntegerType::class,
            'float'    => FloatType::class,
            'text'     => TextType::class,
            'boolean'  => BooleanType::class,
            'datetime' => DateTimeType::class,
            'date'     => DateType::class,
            'time'     => TimeType::class,
        ],
        'remote'    => RemoteType::class,
        'selection' => SelectionType::class,
    ],
];
