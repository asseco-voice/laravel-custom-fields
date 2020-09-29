<?php

declare(strict_types=1);

namespace Voice\CustomFields\Database\Seeders;

use Illuminate\Database\Seeder;
use Voice\CustomFields\App\Form;

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
