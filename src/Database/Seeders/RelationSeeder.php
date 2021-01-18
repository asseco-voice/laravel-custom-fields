<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\Relation;
use Illuminate\Database\Seeder;

class RelationSeeder extends Seeder
{
    public function run(): void
    {
        $customFields = CustomField::all('id');

        $relations = Relation::factory()->count(200)->make()
            ->each(function (Relation $relation) use ($customFields) {
                $relation->timestamps = false;
                $relation->parent_id = $customFields->random(1)->first()->id;
                $relation->child_id = $customFields->random(1)->first()->id;
            })->toArray();

        Relation::query()->insert($relations);
    }
}
