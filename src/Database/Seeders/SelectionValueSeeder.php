<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Contracts\SelectionType;
use Asseco\CustomFields\App\Contracts\SelectionValue;
use Asseco\CustomFields\App\Traits\FakesTypeValues;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SelectionValueSeeder extends Seeder
{
    use FakesTypeValues;

    public function run(): void
    {
        /** @var SelectionType $selectionType */
        $selectionType = app(SelectionType::class);
        /** @var SelectionValue $selectionValueClass */
        $selectionValueClass = app(SelectionValue::class);

        $faker = Factory::create();
        $selectionTypes = $selectionType::with('type')->whereDoesntHave('values')->get();

        $selectionValues = [];
        foreach ($selectionTypes as $selectionType) {
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
                        $selectionValue->value = $this->fakeValueFromType($selectionType->type->name, $faker);
                    })->toArray()
            );

            // We do this to reset fakers unique value list, so that
            // values are unique per custom field instead of database
            $faker->unique(true);
        }

        $selectionValueClass::query()->insert($selectionValues);
    }
}
