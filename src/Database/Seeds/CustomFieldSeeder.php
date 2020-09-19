<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeds;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Voice\CustomFields\App\CustomField;
use Voice\CustomFields\App\PlainType;
use Voice\CustomFields\App\RemoteType;
use Voice\CustomFields\App\SelectionType;
use Voice\CustomFields\App\Traits\FindsTraits;
use Voice\CustomFields\App\Validation;

class CustomFieldSeeder extends Seeder
{
    use FindsTraits;

    public function run(): void
    {
        $now = Carbon::now();
        $faker = Factory::create();
        $traitPath = Config::get('asseco-custom-fields.trait_path');

        $models = $this->getModelsWithTrait($traitPath);
        $types = $this->getTypes();
        $validations = Validation::all('id');

        $amount = 200;
        for ($j = 0; $j < 10; $j++) {
            $data = [];
            for ($i = 0; $i < $amount; $i++) {

                $type = $faker->randomElement($types);

                $data[] = [
                    'selectable_type' => $type['type'],
                    'selectable_id'   => $faker->randomElement($type['values']),
                    'name'            => implode(' ', $faker->words(5)),
                    'label'           => $faker->word,
                    'definition'      => json_encode(["test1" => "test2"]),
                    'model'           => $faker->randomElement($models),
                    'required'        => $faker->boolean(10),
                    'validation_id'   => $validations->random(1)->first()->id,
                    'created_at'      => $now,
                    'updated_at'      => $now
                ];
            }

            CustomField::query()->insert($data);
        }

    }

    protected function getTypes(): array
    {
        return [
            [
                'type'   => PlainType::class,
                'values' => PlainType::all('id')->pluck('id')->toArray()
            ],
            [
                'type'   => SelectionType::class,
                'values' => SelectionType::all('id')->pluck('id')->toArray()
            ],
            [
                'type'   => RemoteType::class,
                'values' => RemoteType::all('id')->pluck('id')->toArray()
            ],
        ];
    }
}
