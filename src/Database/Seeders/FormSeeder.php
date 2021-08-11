<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Contracts\Form;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    public function run(): void
    {
        /** @var Form $form */
        $form = app(Form::class);

        $forms = $form::factory()->count(100)->make()
            ->each(function (Form $form) {
                $form->timestamps = false;
            })
            ->toArray();

        $form::query()->insert($forms);
    }
}
