<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeds;

use Illuminate\Database\Seeder;

class CustomFieldPackageSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CustomFieldTypeSeeder::class,
            CustomFieldValidationSeeder::class,
            CustomFieldSeeder::class,
            CustomFieldRelationSeeder::class
        ]);
    }
}
