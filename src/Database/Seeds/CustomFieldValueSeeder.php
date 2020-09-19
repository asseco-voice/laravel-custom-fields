<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeds;

use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Voice\CustomFields\App\CustomField;
use Voice\CustomFields\App\CustomFieldValue;
use Voice\CustomFields\App\PlainType;
use Voice\CustomFields\App\RemoteType;
use Voice\CustomFields\App\SelectionType;
use Voice\CustomFields\App\Traits\FindsTraits;

class CustomFieldValueSeeder extends Seeder
{
    use FindsTraits;

    protected array $cached = [];

    public function run(): void
    {
        $now = Carbon::now();
        $faker = Factory::create();
        $traitPath = Config::get('asseco-custom-fields.trait_path');

        $models = $this->getModelsWithTrait($traitPath);
        $customFields = CustomField::with('selectable')->get();

        $data = [];
        $amount = 500;
        for ($i = 0; $i < $amount; $i++) {
            $customField = $customFields->random(1)->first();
            $model = $models[array_rand($models)];
            $data[] = $this->generateData($customField, $now, $model, $faker);
        }

        CustomFieldValue::query()->insert($data);
    }

    protected function generateData(CustomField $customField, Carbon $now, string $model, Generator $faker): array
    {
        $initial = $this->initialData($customField->id, $model, $now);

        return $this->switchTypes($customField, $faker, $initial);
    }

    protected function initialData(int $customFieldId, string $model, Carbon $now): array
    {
        return [
            'customizable_type' => $model,
            'customizable_id'   => $this->getCached($model),
            'custom_field_id'   => $customFieldId,
            'created_at'        => $now,
            'updated_at'        => $now,
            'integer'           => null,
            'float'             => null,
            'date'              => null,
            'text'              => null,
            'boolean'           => null,
            'string'            => null,
        ];
    }

    protected function getCached(string $model): int
    {
        if (!array_key_exists($model, $this->cached)) {
            $this->cached[$model] = $model::all('id')->pluck('id')->toArray();
        }

        $cached = $this->cached[$model];
        return $cached[array_rand($cached)];
    }

    protected function switchTypes(CustomField $customField, Generator $faker, array $data): array
    {
        $selectable = $customField->selectable;

        [$type, $value] = $this->getType($selectable);

        switch ($type) {
            case 'integer':
                $data['integer'] = $value ?: $faker->randomNumber();
                break;
            case 'float':
                $data['float'] = $value ?: $faker->randomFloat();
                break;
            case 'date':
                $data['date'] = $value ?: $faker->date();
                break;
            case 'text':
                $data['text'] = $value ?: $faker->sentence;
                break;
            case 'boolean':
                $data['boolean'] = $value ?: $faker->boolean;
                break;
            default:
                $data['string'] = $value ?: $faker->word;
                break;
        }
        return $data;
    }

    protected function getType($selectable)
    {
        if ($selectable instanceof PlainType) {
            return [$selectable->name, null];
        } else if ($selectable instanceof SelectionType) {
            return [$selectable->type->name, $selectable->values->random(1)->first()->value];
        } else if ($selectable instanceof RemoteType) {
            return ['string', null];
        }

        throw new \Exception("Selectable belongs to no known type.");
    }


}
