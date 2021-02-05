<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\RemoteType;
use Asseco\CustomFields\App\Models\SelectionType;
use Asseco\CustomFields\App\Models\Value;
use Asseco\CustomFields\App\Traits\FindsTraits;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;

class ValueSeeder extends Seeder
{
    use FindsTraits;

    protected array $cached = [];

    public function run(): void
    {
        $faker = Factory::create();
        $traitPath = config('asseco-custom-fields.trait_path');

        $models = $this->getModelsWithTrait($traitPath);
        $customFields = CustomField::with('selectable')->get();

        if ($customFields->isEmpty()) {
            echo 'No custom fields available, skipping...';

            return;
        }

        $values = Value::factory()->count(500)->make()
            ->each(function (Value $value) use ($customFields, $models, $faker) {
                $customField = $customFields->random(1)->first();
                $model = $models[array_rand($models)];
                $selectable = $customField->selectable;
                [$type, $typeValue] = $this->getType($selectable);
                $fakeValue = $this->fakeValueFromType($type, $typeValue, $faker);

                $value->timestamps = false;
                $value->model_type = $model;
                $value->model_id = $this->getCached($model);
                $value->custom_field_id = $customField->id;
                $value->{$type} = $fakeValue;
            })->toArray();

        Value::query()->insert($values);
    }

    protected function getCached(string $model): int
    {
        if (!array_key_exists($model, $this->cached)) {
            $this->cached[$model] = $model::all('id')->pluck('id')->toArray();
        }

        $cached = $this->cached[$model];

        return $cached[array_rand($cached)];
    }

    protected function getType($selectable)
    {
        if ($selectable instanceof SelectionType) {
            return [$selectable->type->name, $selectable->values->random(1)->first()->value];
        } elseif ($selectable instanceof RemoteType) {
            return ['string', null];
        } else {
            return [$selectable->name, null];
        }
    }

    protected function fakeValueFromType($type, $value, Generator $faker)
    {
        switch ($type) {
            case 'integer':
                return $value ?: $faker->randomNumber();
            case 'float':
                return $value ?: $faker->randomFloat();
            case 'date':
                return $value ?: $faker->date();
            case 'text':
                return $value ?: $faker->sentence;
            case 'boolean':
                return $value ?: $faker->boolean;
            default:
                return $value ?: $faker->word;
        }
    }
}
