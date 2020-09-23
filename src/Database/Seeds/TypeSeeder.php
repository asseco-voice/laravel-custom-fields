<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Voice\CustomFields\App\RemoteType;
use Voice\CustomFields\App\SelectType;
use Voice\CustomFields\App\Type;
use Voice\CustomFields\App\Types\BooleanType;
use Voice\CustomFields\App\Types\IntegerType;
use Voice\CustomFields\App\Types\StringType;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            [
                'name'   => 'string',
                'map_to' => StringType::class,
            ],
            [
                'name'   => 'integer',
                'map_to' => IntegerType::class,
            ],
            [
                'name'   => 'float',
                'map_to' => 'Voice\\CustomFields\\App\\Type',
            ],
            [
                'name'   => 'date',
                'map_to' => 'Voice\\CustomFields\\App\\Type',
            ],
            [
                'name'   => 'text',
                'map_to' => 'Voice\\CustomFields\\App\\Type',
            ],
            [
                'name'   => 'boolean',
                'map_to' => BooleanType::class,
            ],
            [
                'name'   => 'remote',
                'map_to' => RemoteType::class,
            ],
            [
                'name'   => 'select',
                'map_to' => SelectType::class,
            ]
        ];

        foreach ($data as $item) {
            $item = array_merge($item, ['created_at' => $now, 'updated_at' => $now]);
            Type::query()->updateOrInsert(['name' => $item['name']], $item);
        }
    }
}
