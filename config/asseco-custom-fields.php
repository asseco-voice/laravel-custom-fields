<?php

use Voice\CustomFields\App\PlainTypes\BooleanType;
use Voice\CustomFields\App\PlainTypes\DateType;
use Voice\CustomFields\App\PlainTypes\FloatType;
use Voice\CustomFields\App\PlainTypes\IntegerType;
use Voice\CustomFields\App\PlainTypes\StringType;
use Voice\CustomFields\App\PlainTypes\TextType;
use Voice\CustomFields\App\RemoteType;
use Voice\CustomFields\App\SelectionType;

return [
    /**
     * Path to Laravel models in 'path => namespace' format
     *
     * This does not recurse in folders, so you need to specify
     * an array of paths if non-standard models are to be used
     */
    'models_path' => [
        app_path() => 'App\\'
    ],

    /**
     * Namespace to Customizable trait
     */
    'trait_path'  => 'Voice\CustomFields\App\Traits\Customizable',

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
