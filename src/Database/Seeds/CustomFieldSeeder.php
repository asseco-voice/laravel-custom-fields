<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeds;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Voice\CustomFields\App\CustomField;
use Voice\CustomFields\App\CustomFieldType;
use Voice\CustomFields\App\CustomFieldValidation;
use Voice\CustomFields\App\Traits\FindsTraits;

class CustomFieldSeeder extends Seeder
{
    use FindsTraits;

    public function run(): void
    {
        $faker = Factory::create();
        $traitPath = Config::get('asseco-custom-fields.trait_path');

        $models = $this->getModelsWithTrait($traitPath);

        $type = CustomFieldType::all('id')->pluck('id')->toArray();
        $validation = CustomFieldValidation::all('id')->pluck('id')->toArray();
        $amount = 200;

        $now = Carbon::now();

        for ($j = 0; $j < 10; $j++) {
            $data = [];
            for ($i = 0; $i < $amount; $i++) {
                $data[] = [
                    'name'                       => implode(' ', $faker->words(5)),
                    'label'                      => $faker->word,
                    'definition'                 => json_encode(["test1" => "test2"]),
                    'required'                   => $faker->boolean(10),
                    'model'                      => $models[array_rand($models)],
                    'custom_field_type_id'       => $type[array_rand($type)],
                    'custom_field_validation_id' => $validation[array_rand($validation)],
                    'created_at'                 => $now,
                    'updated_at'                 => $now
                ];
            }

            CustomField::query()->insert($data);
        }

    }
}
