<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Voice\CustomFields\App\CustomField;
use Voice\CustomFields\App\Relation;

class RelationSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $customField = CustomField::all('id');

        $amount = 200;
        $data = [];
        for ($i = 0; $i < $amount; $i++) {
            $data[] = [
                'parent_id'  => $customField->random(1)->first()->id,
                'child_id'   => $customField->random(1)->first()->id,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        Relation::query()->insert($data);
    }
}
