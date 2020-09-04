<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeds;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Voice\CustomFields\App\CustomField;
use Voice\CustomFields\App\CustomFieldType;
use Voice\CustomFields\App\CustomFieldValidation;

class CustomFieldSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();
        $type = CustomFieldType::all('id')->pluck('id')->toArray();
        $validation = CustomFieldValidation::all('id')->pluck('id')->toArray();
        $amount = 5000;
        $data = [];

        for ($i = 0; $i < $amount; $i++) {
            $data[] = [
                'tenant_id'                  => $faker->uuid,
                'name'                       => $faker->word,
                'label'                      => $faker->word,
                'definition'                 => $faker->word,
                'custom_field_type_id'       => $type[array_rand($type)],
                'custom_field_validation_id' => $validation[array_rand($validation)],
                'required'                   => $faker->boolean(10),
            ];
        }

        CustomField::query()->insert($data);
    }
}
