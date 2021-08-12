<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\Contracts\SelectionType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SelectionTypeSeeder extends Seeder
{
    public function run(): void
    {
        /** @var PlainType $plainType */
        $plainType = app(PlainType::class);
        /** @var SelectionType $selectionType */
        $selectionType = app(SelectionType::class);

        $types = $plainType::all('id');

        $selectionTypes = $selectionType::factory()->count(50)->make()
            ->each(function (SelectionType $selectionType) use ($types) {
                if (config('asseco-custom-fields.migrations.uuid')) {
                    $selectionType->id = Str::uuid();
                }

                $selectionType->timestamps = false;
                $selectionType->plain_type_id = $types->random(1)->first()->id;
            })->makeHidden('name')->toArray();

        $selectionType::query()->insert($selectionTypes);
    }
}
