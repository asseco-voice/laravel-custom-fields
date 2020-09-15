<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeds;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Voice\CustomFields\App\Form;

class FormSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();
        $amount = 100;
        $now = Carbon::now();

        $data = [];
        for ($i = 0; $i < $amount; $i++) {

            $data[] = [
                'name'       => implode(' ', $faker->words),
                'definition' => json_encode(["test" => "test"]),
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        Form::query()->insert($data);
    }
}
