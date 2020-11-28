<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Contracts\Mappable;
use Asseco\CustomFields\App\CustomField;
use Asseco\CustomFields\App\ParentType;
use Asseco\CustomFields\App\PlainType;
use Asseco\CustomFields\App\RemoteType;
use Asseco\CustomFields\App\SelectionType;
use Asseco\CustomFields\App\Traits\FindsTraits;
use Asseco\CustomFields\App\Validation;
use Asseco\CustomFields\App\Value;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class CustomFieldSeeder extends Seeder
{
    use FindsTraits;

    public function run(): void
    {
        $traitPath = config('asseco-custom-fields.trait_path');
        $models = $this->getModelsWithTrait($traitPath);

        $types = CustomField::types();
        $plainTypes = PlainType::all('id', 'name');
        $selectionTypes = SelectionType::all('id');
        $remoteTypes = RemoteType::all('id');
        $validations = Validation::all('id');

        for ($j = 0; $j < 20; $j++) {
            $customFields = CustomField::factory()->count(10)->make()
                ->each(function (CustomField $customField) use ($types, $plainTypes, $selectionTypes, $remoteTypes, $validations, $models) {
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

            CustomField::query()->insert($customFields);
        }
    }

    protected function getTypeValue(string $typeClass, string $typeName, Collection $plainTypes, Collection $selectionTypes, Collection $remoteTypes)
    {
        switch ($typeClass) {
            case RemoteType::class:
                return $remoteTypes->random(1)->first()->id;
            case SelectionType::class:
                return $selectionTypes->random(1)->first()->id;
            default:
                return $plainTypes->where('name', $typeName)->first()->id;
        }
    }

    private function shouldValidate(Model $model)
    {
        $column = Value::FALLBACK_VALUE_COLUMN;

        if ($model instanceof Mappable) {
            $column = $model::mapToValueColumn();
        } elseif ($model instanceof ParentType) {
            /**
             * @var $mappable Mappable
             */
            $mappable = $model->subTypeClassPath();
            $column = $mappable::mapToValueColumn();
        }

        return in_array($column, ['string', 'text']);
    }
}
