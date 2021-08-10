<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\Contracts\SelectionType;
use Illuminate\Database\Seeder;

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
                $selectionType->timestamps = false;
                $selectionType->plain_type_id = $types->random(1)->first()->id;
            })->makeHidden('name')->toArray();

        $selectionType::query()->insert($selectionTypes);
    }
}
