<?php

namespace Asseco\CustomFields\Database\Factories;

use Asseco\CustomFields\App\Models\Form;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Form::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'       => implode('_', $this->faker->words(5)),
            'definition' => json_encode(['test' => 'test']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
