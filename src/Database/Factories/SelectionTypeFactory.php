<?php

namespace Asseco\CustomFields\Database\Factories;

use Asseco\CustomFields\App\SelectionType;
use Illuminate\Database\Eloquent\Factories\Factory;

class SelectionTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SelectionType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'plain_type_id' => null,
            'multiselect'   => $this->faker->boolean,
            'created_at'    => now(),
            'updated_at'    => now(),
        ];
    }
}
