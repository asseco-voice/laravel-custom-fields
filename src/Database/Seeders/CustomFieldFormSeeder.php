<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\Form;
use Illuminate\Database\Seeder;

class CustomFieldFormSeeder extends Seeder
{
    public function run(): void
    {
        $forms = Form::all();
        $customFields = CustomField::all();

        if ($customFields->isEmpty()) {
            echo 'No custom fields available, skipping...';

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
