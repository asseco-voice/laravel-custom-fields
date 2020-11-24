<?php

namespace Voice\CustomFields\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Voice\CustomFields\App\Form;

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
            'name'       => implode(' ', $this->faker->words(5)),
            'definition' => json_encode(['test' => 'test']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
