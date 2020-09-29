<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeders;

use Illuminate\Database\Seeder;
use Voice\CustomFields\App\Validation;

class ValidationSeeder extends Seeder
{
    public function run(): void
    {
        $validations = Validation::factory()->count(200)->make()
            ->each(function (Validation $validation) {
                $validation->timestamps = false;
            })
            ->toArray();

        Validation::query()->insert($validations);
    }
}
