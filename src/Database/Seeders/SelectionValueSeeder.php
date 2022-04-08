<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Contracts\SelectionType;
use Asseco\CustomFields\App\Contracts\SelectionValue;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SelectionValueSeeder extends Seeder
{
    public function run(): void
    {
        /** @var SelectionType $selectionType */
        $selectionType = app(SelectionType::class);
        /** @var SelectionValue $selectionValueClass */
        $selectionValueClass = app(SelectionValue::class);

        $faker = Factory::create();
        $amount = 50;
        $selectionTypes = $selectionType::with('type')->get();

        $selectionValues = [];
        for ($i = 0; $i < $amount; $i++) {

            $selectionType = $selectionTypes->random(1)->first();

            if ($selectionType->type->name === 'boolean') {
                $number = 2;
            } else {
                // Have random number of values set for a single type
                $number = rand(3, 10);
            }

            $selectionValues = array_merge_recursive($selectionValues,
                $selectionValueClass::factory()->count($number)->make()
                    ->each(function (SelectionValue $selectionValue) use ($selectionType, $faker) {
                        if (config('asseco-custom-fields.migrations.uuid')) {
                            $selectionValue->id = Str::uuid();
                        }

                        $selectionValue->timestamps = false;
                        $selectionValue->selection_type_id = $selectionType->id;
                        $selectionValue->value = $this->getTypeValue($selectionType, $faker);
                    })->toArray()
            );

            // We do this to reset fakers unique value list, so that
            // values are unique per custom field instead of database
            $faker->unique($reset = true);
        }

        $selectionValueClass::query()->insert($selectionValues);
    }

    protected function getTypeValue(SelectionType $selectionType, Generator $faker)
    {
        $plainType = $selectionType->type->name;

        switch ($plainType) {
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
