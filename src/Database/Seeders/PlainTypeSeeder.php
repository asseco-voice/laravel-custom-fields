<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\PlainType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

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
