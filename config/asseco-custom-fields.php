<?php

use Asseco\CustomFields\App\Models\RemoteType;
use Asseco\CustomFields\App\Models\SelectionType;
use Asseco\CustomFields\App\PlainTypes\BooleanType;
use Asseco\CustomFields\App\PlainTypes\DateType;
use Asseco\CustomFields\App\PlainTypes\FloatType;
use Asseco\CustomFields\App\PlainTypes\IntegerType;
use Asseco\CustomFields\App\PlainTypes\StringType;
use Asseco\CustomFields\App\PlainTypes\TextType;
use Asseco\CustomFields\App\Traits\Customizable;

return [
    /**
     * Path to Laravel models in 'path => namespace' format.
     *
     * This does not recurse in folders, so you need to specify
     * an array of paths if non-standard models are to be used
     */
    'models_path' => [
        app_path() => 'App\\Models\\',
    ],

    /**
     * Namespace to Customizable trait.
     */
    'trait_path'  => Customizable::class,

    'type_mappings' => [
        'plain'     => [
            'string'  => StringType::class,
            'integer' => IntegerType::class,
            'float'   => FloatType::class,
            'date'    => DateType::class,
            'text'    => TextType::class,
            'boolean' => BooleanType::class,
        ],
        'remote'    => RemoteType::class,
        'selection' => SelectionType::class,
    ],
];
