<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeds;

use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Voice\CustomFields\App\SelectType;
use Voice\CustomFields\App\SelectValue;

class SelectValueSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $faker = Factory::create();
        $amount = 50;
        $selectTypes = SelectType::with('type')->get();

        $data = [];
        for ($i = 0; $i < $amount; $i++) {

            $random = rand(3, 10);

            for ($j = 0; $j < $random; $j++) {
                $selectType = $selectTypes->random(1)->first();
                $data[] = [
                    'select_type_id' => $selectType->id,
                    'label'          => $faker->word,
                    'value'          => $this->getTypeValue($selectType, $faker),
                    'created_at'     => $now,
                    'updated_at'     => $now
                ];
            }
        }

        SelectValue::query()->insert($data);
    }

    protected function getTypeValue(SelectType $selectTypes, Generator $faker)
    {
        $plainType = $selectTypes->type->name;

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
