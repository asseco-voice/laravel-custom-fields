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

            // Have random number of values set for a single type
            $random = rand(3, 10);

            $selectionValues = array_merge_recursive($selectionValues,
                $selectionValueClass::factory()->count($random)->make()
                    ->each(function (SelectionValue $selectionValue) use ($selectionTypes, $faker) {

                        if (config('asseco-custom-fields.migrations.uuid')) {
                            $selectionValue->id = Str::uuid();
                        }

                        $selectionType = $selectionTypes->random(1)->first();

                        $selectionValue->timestamps = false;
                        $selectionValue->selection_type_id = $selectionType->id;
                        $selectionValue->value = $this->getTypeValue($selectionType, $faker);
                    })->toArray()
            );
        }

        $selectionValueClass::query()->insert($selectionValues);
    }

    protected function getTypeValue(SelectionType $selectionType, Generator $faker)
    {
        $plainType = $selectionType->type->name;

        switch ($plainType) {
            case 'integer':
                return $faker->randomNumber();
            case 'float':
                return $faker->randomFloat();
            case 'date':
                return $faker->date();
            case 'time':
                return $faker->time();
            case 'datetime':
                return $faker->datetime();
            case 'text':
                return $faker->sentence;
            case 'boolean':
                return $faker->boolean;
            default:
                return $faker->word;
        }
    }
}
