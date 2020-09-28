<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Voice\CustomFields\App\PlainType;
use Voice\CustomFields\App\RemoteType;

class RemoteTypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $faker = Factory::create();
        $amount = 50;

        $data = [];
        $methods = ['GET', 'POST', 'PUT'];

        // Force casting remote types to string unless we decide on different implementation.
        $plainTypeId = PlainType::query()->where('name', 'string')->firstOrFail()->id;

        for ($i = 0; $i < $amount; $i++) {
            $data[] = [
                'plain_type_id' => $plainTypeId,
                'url'           => $faker->url,
                'method'        => $faker->randomElement($methods),
                'body'          => $faker->sentence,
                'created_at'    => $now,
                'updated_at'    => $now
            ];
        }

        RemoteType::query()->insert($data);
    }
}
