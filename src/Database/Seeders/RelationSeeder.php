<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\Relation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RelationSeeder extends Seeder
{
    public function run(): void
    {
        /** @var CustomField $customField */
        $customField = app(CustomField::class);
        /** @var Relation $relation */
        $relation = app(Relation::class);

        $customFields = $customField::all('id');

        if ($customFields->isEmpty()) {
            echo "No custom fields available, skipping...\n";

            return;
        }

        $relations = $relation::factory()->count(200)->make()
            ->each(function (Relation $relation) use ($customFields) {
                if(config('asseco-custom-fields.migrations.uuid')){
                    $relation->id = Str::uuid();
                }

                $relation->timestamps = false;
                $relation->parent_id = $customFields->random(1)->first()->id;
                $relation->child_id = $customFields->random(1)->first()->id;
            })->toArray();

        $relation::query()->insert($relations);
    }
}
