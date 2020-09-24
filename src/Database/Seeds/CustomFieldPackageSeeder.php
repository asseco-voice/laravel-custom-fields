<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class CustomFieldPackageSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PlainTypeSeeder::class,
        ]);

        if (Config::get('app.env') !== 'production') {
            $this->call([
                RemoteTypeSeeder::class,
                SelectionTypeSeeder::class,
                SelectionValueSeeder::class,

                ValidationSeeder::class,
                CustomFieldSeeder::class,
                RelationSeeder::class,

                CustomFieldValueSeeder::class,

                FormSeeder::class,
                CustomFieldFormSeeder::class
            ]);
        }
    }
}
