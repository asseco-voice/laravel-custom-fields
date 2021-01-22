<?php

namespace Asseco\CustomFields\Database\Factories;

use Asseco\CustomFields\App\Models\SelectionValue;
use Illuminate\Database\Eloquent\Factories\Factory;

class SelectionValueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SelectionValue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'selection_type_id' => $this->faker->randomNumber(),
            'label'             => $this->faker->word,
            'preselect'         => $this->faker->boolean(10),
            'value'             => $this->faker->word,
            'created_at'        => now(),
            'updated_at'        => now(),
        ];
    }
}
