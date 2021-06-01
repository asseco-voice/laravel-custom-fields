<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Illuminate\Database\Seeder;

class CustomFieldPackageSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PlainTypeSeeder::class,
        ]);

        if (config('app.env') !== 'production') {
            $this->call([
                RemoteTypeSeeder::class,
                SelectionTypeSeeder::class,
                SelectionValueSeeder::class,

                ValidationSeeder::class,
                CustomFieldSeeder::class,
                RelationSeeder::class,

                ValueSeeder::class,

                FormSeeder::class,
                CustomFieldFormSeeder::class,
            ]);
        }
    }
}
