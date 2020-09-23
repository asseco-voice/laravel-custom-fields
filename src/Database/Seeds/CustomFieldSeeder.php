<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeds;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Voice\CustomFields\App\CustomField;
use Voice\CustomFields\App\PlainType;
use Voice\CustomFields\App\RemoteType;
use Voice\CustomFields\App\SelectType;
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

        $types = Config::get('asseco-custom-fields.type_mappings');

        $plainTypes = PlainType::all('id', 'name');
        $selectTypes = SelectType::all('id');
        $remoteTypes = RemoteType::all('id');

        $validations = Validation::all('id');

        $amount = 200;
        for ($j = 0; $j < 10; $j++) {
            $data = [];
            for ($i = 0; $i < $amount; $i++) {

                $typeName = array_rand($types);
                $typeClass = $types[$typeName];

                $typeValue = $this->getTypeValue($typeClass, $typeName, $plainTypes, $selectTypes, $remoteTypes);

                $data[] = [
                    'selectable_type' => $typeClass,
                    'selectable_id'   => $typeValue,
                    'name'            => implode(' ', $faker->words(5)),
                    'label'           => $faker->word,
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

    protected function getTypeValue(string $typeClass, string $typeName, Collection $plainTypes, Collection $selectTypes, Collection $remoteTypes)
    {
        switch ($typeClass) {
            case RemoteType::class:
                return $remoteTypes->random(1)->first()->id;
            case SelectType::class:
                return $selectTypes->random(1)->first()->id;
            default:
                return $plainTypes->where('name', $typeName)->first()->id;
        }
    }
}
