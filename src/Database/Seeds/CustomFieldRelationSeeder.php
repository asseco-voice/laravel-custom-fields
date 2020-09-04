<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeds;

use Illuminate\Database\Seeder;
use Voice\CustomFields\App\CustomField;
use Voice\CustomFields\App\CustomFieldRelation;

class CustomFieldRelationSeeder extends Seeder
{
    public function run(): void
    {
        $customField = CustomField::all('id')->pluck('id')->toArray();
        $amount = 200;
        $data = [];

        for ($i = 0; $i < $amount; $i++) {
            $data[] = [
                'custom_field_parent_id' => $customField[array_rand($customField)],
                'custom_field_child_id'  => $customField[array_rand($customField)],
            ];
        }

        CustomFieldRelation::query()->insert($data);
    }
}
