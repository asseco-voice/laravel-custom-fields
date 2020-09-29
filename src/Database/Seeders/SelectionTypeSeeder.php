<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeders;

use Illuminate\Database\Seeder;
use Voice\CustomFields\App\PlainType;
use Voice\CustomFields\App\SelectionType;

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
