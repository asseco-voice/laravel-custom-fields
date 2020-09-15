<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeds;

use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Voice\CustomFields\App\CustomField;
use Voice\CustomFields\App\CustomFieldType;
use Voice\CustomFields\App\CustomFieldValue;
use Voice\CustomFields\App\Traits\FindsTraits;

class CustomFieldValueSeeder extends Seeder
{
    use FindsTraits;

    protected array $cached = [];

    public function run(): void
    {
        $faker = Factory::create();

        $traitPath = Config::get('asseco-custom-fields.trait_path');

        $models = $this->getModelsWithTrait($traitPath);
        $customFields = CustomField::all();
        $customFieldTypes = CustomFieldType::all();
        $amount = 500;

        $now = Carbon::now();

        $data = [];
        for ($i = 0; $i < $amount; $i++) {
            $data[] = $this->generateData($customFields->random(1)->first(), $now, $models, $customFieldTypes, $faker);
        }

        CustomFieldValue::query()->insert($data);
    }

    protected function generateData(CustomField $customField, Carbon $now, array $models, Collection $customFieldTypes, Generator $faker): array
    {
        $customFieldType = $customFieldTypes->where('id', $customField->custom_field_type_id)->first();

        $model = $models[array_rand($models)];

        $data = [
            'custom_field_id'   => $customField->id,
            'customizable_type' => $model,
            'customizable_id'   => $this->getCached($model),
            'created_at'        => $now,
            'updated_at'        => $now,
            'integer'           => null,
            'float'             => null,
            'date'              => null,
            'text'              => null,
            'boolean'           => null,
            'string'            => null,
        ];

        switch ($customFieldType) {
            case 'integer':
                $data['integer'] = $faker->randomNumber();
                break;
            case 'float':
                $data['float'] = $faker->randomFloat();
                break;
            case 'date':
                $data['date'] = $faker->date();
                break;
            case 'text':
                $data['text'] = $faker->sentence;
                break;
            case 'boolean':
                $data['boolean'] = $faker->boolean;
                break;
            default:
                $data['string'] = $faker->word;
                break;
        }

        return $data;
    }

    protected function getCached(string $model): int
    {
        if (!array_key_exists($model, $this->cached)) {
            $this->cached[$model] = $model::all('id')->pluck('id')->toArray();
        }

        $cached = $this->cached[$model];
        return $cached[array_rand($cached)];
    }
}
