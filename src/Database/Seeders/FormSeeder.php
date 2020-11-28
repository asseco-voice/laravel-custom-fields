<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Illuminate\Database\Seeder;
use Asseco\CustomFields\App\Form;

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
