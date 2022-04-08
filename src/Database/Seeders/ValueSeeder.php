<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\RemoteType;
use Asseco\CustomFields\App\Contracts\SelectionType;
use Asseco\CustomFields\App\Contracts\Value;
use Asseco\CustomFields\App\Traits\FindsTraits;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ValueSeeder extends Seeder
{
    use FindsTraits;

    protected array $cached = [];

    public function run(): void
    {
        /** @var CustomField $customFieldClass */
        $customFieldClass = app(CustomField::class);
        /** @var Value $valueClass */
        $valueClass = app(Value::class);

        $faker = Factory::create();
        $traitPath = config('asseco-custom-fields.trait_path');

        $models = $this->getModelsWithTrait($traitPath);
        $customFields = $customFieldClass::all();

        if ($customFields->isEmpty()) {
            echo "No custom fields available, skipping...\n";

            return;
        }

        $values = $valueClass::factory()->count(500)->make()
            ->each(function (Value $value) use ($customFields, $models, $faker) {
                if (config('asseco-custom-fields.migrations.uuid')) {
                    $value->id = Str::uuid();
                }

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
            })->makeHidden('value')->toArray();

        $valueClass::query()->insert($values);
    }

    protected function getCached(string $model): string
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
            return [$selectable->type->name, null];
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
            case 'time':
                return $value ?: $faker->time();
            case 'datetime':
                return $value ?: $faker->datetime();
            case 'text':
                return $value ?: $faker->sentence;
            case 'boolean':
                return $value ?: $faker->boolean;
            default:
                return $value ?: $faker->word;
        }
    }
}
