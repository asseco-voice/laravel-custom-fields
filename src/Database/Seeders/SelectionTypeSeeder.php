<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Models\PlainType;
use Asseco\CustomFields\App\Models\SelectionType;
use Illuminate\Database\Seeder;

class SelectionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = PlainType::all('id');

        $selectionTypes = SelectionType::factory()->count(50)->make()
            ->each(function (SelectionType $selectionType) use ($types) {
                $selectionType->timestamps = false;
                $selectionType->plain_type_id = $types->random(1)->first()->id;
            })->makeHidden('name')->toArray();

        SelectionType::query()->insert($selectionTypes);
    }
}
