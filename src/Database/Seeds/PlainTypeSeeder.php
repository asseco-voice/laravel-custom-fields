<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Voice\CustomFields\App\PlainType;

class PlainTypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            ['name' => 'string'],
            ['name' => 'integer'],
            ['name' => 'float'],
            ['name' => 'date'],
            ['name' => 'text'],
            ['name' => 'boolean']
        ];

        foreach ($data as $item) {
            PlainType::query()->updateOrInsert(['name' => $item['name']],
                [
                    'name'       => $item['name'],
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
        }
    }
}
