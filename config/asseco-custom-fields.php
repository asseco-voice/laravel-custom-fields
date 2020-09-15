<?php

return [
    /**
     * Path to Laravel models in 'path => namespace' format
     *
     * This does not recurse in folders, so you need to specify
     * an array of paths if non-standard models are to be used
     */
    'models_path'      => [
        app_path() => 'App\\'
    ],

    /**
     * Namespace to Customizable trait
     */
    'trait_path'      => 'Voice\CustomFields\App\Traits\Customizable',
];
