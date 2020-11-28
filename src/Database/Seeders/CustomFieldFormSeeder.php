<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Illuminate\Database\Seeder;
use Asseco\CustomFields\App\CustomField;
use Asseco\CustomFields\App\Form;

class CustomFieldFormSeeder extends Seeder
{
    public function run(): void
    {
        $forms = Form::all();
        $customFields = CustomField::all();

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
