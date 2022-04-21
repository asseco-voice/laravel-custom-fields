<?php

namespace Asseco\CustomFields\App\Traits;

use Faker\Generator;

trait FakesTypeValues
{
    protected function fakeValueFromType($type, Generator $faker)
    {
        switch ($type) {
            case 'integer':
                return $faker->unique()->randomNumber();
            case 'float':
                return $faker->unique()->randomFloat();
            case 'date':
                return $faker->unique()->date();
            case 'time':
                return $faker->unique()->time();
            case 'datetime':
                return $faker->unique()->datetime();
            case 'text':
                return $faker->unique()->sentence;
            case 'boolean':
                return $faker->unique()->boolean;
            default:
                return $faker->unique()->word;
        }
    }
}
