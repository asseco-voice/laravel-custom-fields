<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Voice\CustomFields\App\Validation;

class ValidationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();
        $amount = 200;
        $data = [];

        $now = Carbon::now();

        for ($i = 0; $i < $amount; $i++) {
            $data[] = [
                'name'       => implode(' ', $faker->words(5)),
                'validation' => '/some_regex/',
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        Validation::query()->insert($data);
    }
}
