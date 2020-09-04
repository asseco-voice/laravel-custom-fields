<?php

declare(strict_types=1);

namespace Voice\JsonAuthorization\Database\Seeds;

use Illuminate\Database\Seeder;
use Voice\CustomFields\App\CustomField;

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

        CustomField::query()->insert($data);
    }
}
