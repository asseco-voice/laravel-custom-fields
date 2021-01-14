<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Models\Form;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    public function run(): void
    {
        $forms = Form::factory()->count(100)->make()
            ->each(function (Form $form) {
                $form->timestamps = false;
            })
            ->toArray();

        Form::query()->insert($forms);
    }
}
