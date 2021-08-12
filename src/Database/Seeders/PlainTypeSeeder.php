<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Contracts\PlainType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlainTypeSeeder extends Seeder
{
    public function run(): void
    {
        /** @var PlainType $plainType */
        $plainType = app(PlainType::class);

        $types = array_keys(config('asseco-custom-fields.plain_types'));

        $plainTypes = [];
        foreach ($types as $type) {
            if (config('asseco-custom-fields.migrations.uuid')) {
                $plainTypes[] = [
                    'id'   => Str::uuid(),
                    'name' => $type,
                ];
            } else {
                $plainTypes[] = ['name' => $type];
            }
        }

        $plainType::query()->upsert($plainTypes, ['name'], ['name']);
    }
}
