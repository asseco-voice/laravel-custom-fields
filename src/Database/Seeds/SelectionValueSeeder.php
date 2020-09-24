<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeds;

use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Voice\CustomFields\App\SelectionType;
use Voice\CustomFields\App\SelectionValue;

class SelectionValueSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $faker = Factory::create();
        $amount = 50;
        $selectionTypes = SelectionType::with('type')->get();

        $data = [];
        for ($i = 0; $i < $amount; $i++) {

            $random = rand(3, 10);

            for ($j = 0; $j < $random; $j++) {
                $selectionType = $selectionTypes->random(1)->first();
                $data[] = [
                    'selection_type_id' => $selectionType->id,
                    'label'             => $faker->word,
                    'value'             => $this->getTypeValue($selectionType, $faker),
                    'created_at'        => $now,
                    'updated_at'        => $now
                ];
            }
        }

        SelectionValue::query()->insert($data);
    }

    protected function getTypeValue(SelectionType $selectionType, Generator $faker)
    {
        $plainType = $selectionType->type->name;

        switch ($plainType) {
            case 'integer':
                return $faker->randomNumber();
            case 'float':
                return $faker->randomFloat();
            case 'date':
                return $faker->date();
            case 'text':
                return $faker->sentence;
            case 'boolean':
                return $faker->boolean;
            default:
                return $faker->word;
        }
    }
}
