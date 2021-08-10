<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Contracts\PlainType;
use Illuminate\Database\Seeder;

class PlainTypeSeeder extends Seeder
{
    public function run(): void
    {
        /** @var PlainType $plainType */
        $plainType = app(PlainType::class);

        $types = array_keys(config('asseco-custom-fields.plain_types'));

        $plainTypes = [];
        foreach ($types as $type) {
            $plainTypes[] = ['name' => $type];
        }

        $plainType::query()->upsert($plainTypes, 'name');
    }
}
