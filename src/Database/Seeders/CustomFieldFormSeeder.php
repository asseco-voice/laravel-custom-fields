<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\Form;
use Illuminate\Database\Seeder;

class CustomFieldFormSeeder extends Seeder
{
    public function run(): void
    {
        /** @var Form $form */
        $form = app(Form::class);
        /** @var CustomField $customField */
        $customField = app(CustomField::class);

        $forms = $form::all();
        $customFields = $customField::all();

        if ($customFields->isEmpty()) {
            echo "No custom fields available, skipping...\n";

            return;
        }

        foreach ($forms as $form) {
            $rand = rand(1, 10);

            $ids = [];
            for ($i = 0; $i < $rand; $i++) {
                $ids[] = $customFields->random(1)->first()->id;
            }

            $form->customFields()->sync($ids);
        }
    }
}
