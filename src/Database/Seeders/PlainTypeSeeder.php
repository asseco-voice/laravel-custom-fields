<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Voice\CustomFields\App\PlainType;

class PlainTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = config('asseco-custom-fields.type_mappings.plain');

        $plainTypes = [];
        foreach ($types as $typeName => $typeClass) {
            $plainType = PlainType::factory()->make(['name' => $typeName]);
            $plainType->timestamps = false;
            $plainTypes[] = $plainType->toArray();
        }

        foreach ($plainTypes as $plainType) {
            PlainType::query()->updateOrInsert(['name' => $plainType['name']], $plainType);
        }
    }
}
