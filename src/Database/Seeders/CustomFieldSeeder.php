<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Contracts\Mappable;
use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\ParentType;
use Asseco\CustomFields\App\Models\PlainType;
use Asseco\CustomFields\App\Models\RemoteType;
use Asseco\CustomFields\App\Models\SelectionType;
use Asseco\CustomFields\App\Models\Validation;
use Asseco\CustomFields\App\Models\Value;
use Asseco\CustomFields\App\Traits\FindsTraits;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class CustomFieldSeeder extends Seeder
{
    use FindsTraits;

    public function run(): void
    {
        $traitPath = config('asseco-custom-fields.trait_path');
        $models = $this->getModelsWithTrait($traitPath);

        if(!$models){
            return;
        }
        
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
             * @var Mappable $mappable
             */
            $mappable = $model->subTypeClassPath();
            $column = $mappable::mapToValueColumn();
        }

        return in_array($column, ['string', 'text']);
    }
}
