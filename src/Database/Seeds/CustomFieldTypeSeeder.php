<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Voice\CustomFields\App\CustomFieldType;

class CustomFieldTypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        
        $data = [
            [
                'name'       => 'string',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name'       => 'integer',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name'       => 'float',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name'       => 'date',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name'       => 'text',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name'       => 'boolean',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        CustomFieldType::query()->insert($data);
    }
}
