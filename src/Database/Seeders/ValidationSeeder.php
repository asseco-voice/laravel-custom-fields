<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Illuminate\Database\Seeder;
use Asseco\CustomFields\App\Validation;

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
