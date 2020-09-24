<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeds;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Voice\CustomFields\App\PlainType;
use Voice\CustomFields\App\SelectionType;

class SelectionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $faker = Factory::create();
        $amount = 50;
        $types = PlainType::all('id');

        $data = [];
        for ($i = 0; $i < $amount; $i++) {
            $data[] = [
                'plain_type_id' => $types->random(1)->first()->id,
                'multiselect'   => $faker->boolean,
                'created_at'    => $now,
                'updated_at'    => $now
            ];
        }

        SelectionType::query()->insert($data);
    }
}
