<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Contracts\SelectionType;
use Asseco\CustomFields\App\Contracts\SelectionValue;
use Asseco\CustomFields\App\Traits\FakesTypeValues;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SelectionValueSeeder extends Seeder
{
    use FakesTypeValues;

    public function run(): void
    {
        /** @var SelectionType $selectionTypeClass */
        $selectionTypeClass = app(SelectionType::class);
        /** @var SelectionValue $selectionValueClass */
        $selectionValueClass = app(SelectionValue::class);

        $faker = Factory::create();
        $selectionTypes = $selectionTypeClass::with('type')->whereDoesntHave('values')->get();

        $selectionValues = [];
        foreach ($selectionTypes as $selectionType) {
            // Merging to enable single insert for performance reasons
            $selectionValues = array_merge_recursive($selectionValues,
                $this->makeValue($selectionValueClass, $selectionType, $faker)
            );

            // We do this to reset fakers unique value list, so that
            // values are unique per custom field instead of database
            $faker->unique(true);
        }

        // When comparing type name, this gets appended to the model
        // which breaks the insert if not removed
        foreach ($selectionValues as &$selectionValue) {
            unset($selectionValue['type']);
        }

        $selectionValueClass::query()->insert($selectionValues);
    }

    protected function makeValue(SelectionValue $selectionValueClass, SelectionType $selectionType, Generator $faker): array
    {
        // Have random number of values set for a single type, 2 for boolean plain type
        $number = $selectionType->type->name === 'boolean' ? 2 : rand(3, 10);

        return $selectionValueClass::factory()->count($number)->make()
            ->each(function (SelectionValue $selectionValue) use ($selectionType, $faker) {
                if (config('asseco-custom-fields.migrations.uuid')) {
                    $selectionValue->id = Str::uuid()->toString();
                }

                $selectionValue->timestamps = false;
                $selectionValue->selection_type_id = $selectionType->id;
                $selectionValue->value = $this->fakeValueFromType($selectionType->type->name, $faker);
            })->toArray();
    }
}
