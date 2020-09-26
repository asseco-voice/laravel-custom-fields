<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Voice\CustomFields\App\PlainType;

class PlainTypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $types = Config::get('asseco-custom-fields.type_mappings.plain');

        $data = [];
        foreach ($types as $typeName => $typeClass) {
            $data[] = [
                'name'       => $typeName,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        foreach ($data as $item) {
            PlainType::query()->updateOrInsert(['name' => $item['name']], $item);
        }
    }
}
