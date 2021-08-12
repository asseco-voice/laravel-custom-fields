<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\Mappable;
use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\Contracts\RemoteType;
use Asseco\CustomFields\App\Contracts\SelectionType;
use Asseco\CustomFields\App\Contracts\Validation;
use Asseco\CustomFields\App\Contracts\Value;
use Asseco\CustomFields\App\Models\ParentType;
use Asseco\CustomFields\App\Traits\FindsTraits;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CustomFieldSeeder extends Seeder
{
    use FindsTraits;

    public function run(): void
    {
        $traitPath = config('asseco-custom-fields.trait_path');
        $models = $this->getModelsWithTrait($traitPath);

        if (!$models) {
            echo "No models with Customizable trait available, skipping...\n";

            return;
        }

        /** @var CustomField $customField */
        $customField = app(CustomField::class);
        /** @var PlainType $plainType */
        $plainType = app(PlainType::class);
        /** @var SelectionType $selectionType */
        $selectionType = app(SelectionType::class);
        /** @var RemoteType $remoteType */
        $remoteType = app(RemoteType::class);
        /** @var Validation $validation */
        $validation = app(Validation::class);

        $types = $customField::types();
        $plainTypes = $plainType::all('id', 'name');
        $selectionTypes = $selectionType::all('id');
        $remoteTypes = $remoteType::all('id');
        $validations = $validation::all('id');

        for ($j = 0; $j < 20; $j++) {
            $customFields = $customField::factory()->count(10)->make()
                ->each(function (CustomField $customField) use ($types, $plainTypes, $selectionTypes, $remoteTypes, $validations, $models) {
                    if(config('asseco-custom-fields.migrations.uuid')){
                        $customField->id = Str::uuid();
                    }

                    $typeName = array_rand($types);
                    $typeClass = $types[$typeName];
                    $typeValue = $this->getTypeValue($typeClass, $typeName, $plainTypes, $selectionTypes, $remoteTypes);
                    $shouldValidate = $this->shouldValidate($typeClass::find($typeValue));

                    $customField->timestamps = false;
                    $customField->selectable_type = $typeClass;
                    $customField->selectable_id = $typeValue;
                    $customField->model = $models[array_rand($models)];
                    $customField->validation_id = $shouldValidate ? $validations->random(1)->first()->id : null;
                })->toArray();

            $customField::query()->insert($customFields);
        }
    }

    protected function getTypeValue(string $typeClass, string $typeName, Collection $plainTypes, Collection $selectionTypes, Collection $remoteTypes)
    {
        switch ($typeClass) {
            case config('asseco-custom-fields.models.remote_type'):
                return $remoteTypes->random(1)->first()->id;
            case config('asseco-custom-fields.models.selection_type'):
                return $selectionTypes->random(1)->first()->id;
            default:
                return $plainTypes->where('name', $typeName)->first()->id;
        }
    }

    private function shouldValidate(Model $model)
    {
        /** @var Value $value */
        $value = app(Value::class);

        $column = $value::FALLBACK_VALUE_COLUMN;

        if ($model instanceof Mappable) {
            $column = $model::mapToValueColumn();
        } elseif ($model instanceof ParentType) {
            /**
             * @var Mappable $mappable
             */
            $mappable = $model->subTypeClassPath();
            $column = $mappable::mapToValueColumn();
        }

        return in_array($column, ['string', 'text']);
    }
}
