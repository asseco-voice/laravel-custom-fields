<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Voice\CustomFields\App\Contracts\Mappable;
use Voice\CustomFields\App\CustomField;
use Voice\CustomFields\App\ParentType;
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

        $types = CustomField::types();
        $plainTypes = PlainType::all('id', 'name');
        $selectionTypes = SelectionType::all('id');
        $remoteTypes = RemoteType::all('id');
        $validations = Validation::all('id');

        $amount = 200;
        for ($j = 0; $j < 10; $j++) {
            $data = [];
            for ($i = 0; $i < $amount; $i++) {

                $typeName = array_rand($types);
                $typeClass = $types[$typeName];
                $typeValue = $this->getTypeValue($typeClass, $typeName, $plainTypes, $selectionTypes, $remoteTypes);
                $shouldValidate = $this->shouldValidate($typeClass::find($typeValue));

                $data[] = [
                    'selectable_type' => $typeClass,
                    'selectable_id'   => $typeValue,
                    'name'            => implode(' ', $faker->words(5)),
                    'label'           => $faker->word,
                    'placeholder'     => $faker->word,
                    'model'           => $faker->randomElement($models),
                    'required'        => $faker->boolean(10),
                    'validation_id'   => $shouldValidate ? $validations->random(1)->first()->id : null,
                    'created_at'      => $now,
                    'updated_at'      => $now
                ];
            }

            CustomField::query()->insert($data);
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
        $column = Mappable::DEFAULT_COLUMN;

        if ($model instanceof Mappable) {
            $column = $model::mapToValueColumn();
        } else if ($model instanceof ParentType) {
            /**
             * @var $mappable Mappable
             */
            $mappable = $model->subTypeClassPath();
            $column = $mappable::mapToValueColumn();
        }

        return in_array($column, ['string', 'text']);
    }
}
