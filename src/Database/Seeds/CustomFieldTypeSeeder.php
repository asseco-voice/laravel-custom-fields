<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeds;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Voice\CustomFields\App\CustomFieldType;

class CustomFieldTypeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();
        $amount = 200;
        $data = [];

        for ($i = 0; $i < $amount; $i++) {
            $data[] = [
                'name' => implode(' ', $faker->words),
            ];
        }

        CustomFieldType::query()->insert($data);
    }
}
