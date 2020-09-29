<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeders;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Voice\CustomFields\App\SelectionType;
use Voice\CustomFields\App\SelectionValue;

class SelectionValueSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();
        $amount = 50;
        $selectionTypes = SelectionType::with('type')->get();

        $selectionValues = [];
        for ($i = 0; $i < $amount; $i++) {

            // Have random number of values set for a single type
            $random = rand(3, 10);

            $selectionValues = array_merge_recursive($selectionValues,
                SelectionValue::factory()->count($random)->make()
                    ->each(function (SelectionValue $selectionValue) use ($selectionTypes, $faker) {
                        $selectionType = $selectionTypes->random(1)->first();

                        $selectionValue->timestamps = false;
                        $selectionValue->selection_type_id = $selectionType->id;
                        $selectionValue->value = $this->getTypeValue($selectionType, $faker);
                    })->toArray()
            );

            SelectionValue::query()->insert($selectionValues);
        }

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
            case 'text':
                return $faker->sentence;
            case 'boolean':
                return $faker->boolean;
            default:
                return $faker->word;
        }
    }
}
