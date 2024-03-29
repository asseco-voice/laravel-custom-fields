<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Contracts\Validation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ValidationSeeder extends Seeder
{
    public function run(): void
    {
        /** @var Validation $validation */
        $validation = app(Validation::class);

        $validations = $validation::factory()->count(200)->make([
            'id' => function () {
                if (config('asseco-custom-fields.migrations.uuid')) {
                    return Str::uuid();
                }

                return null;
            },
        ])->each(function (Validation $validation) {
            $validation->timestamps = false;
        })->toArray();

        $validation::query()->insert($validations);
    }
}
