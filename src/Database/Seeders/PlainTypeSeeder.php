<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Models\PlainType;
use Illuminate\Database\Seeder;

class PlainTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = array_keys(config('asseco-custom-fields.type_mappings.plain'));

        $plainTypes = [];
        foreach ($types as $type) {
            $plainTypes[] = ['name' => $type];
        }

        PlainType::query()->upsert($plainTypes, 'name');
    }
}
