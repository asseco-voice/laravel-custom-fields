<?php

namespace Asseco\CustomFields\Database\Factories;

use Asseco\CustomFields\App\Models\Value;
use Illuminate\Database\Eloquent\Factories\Factory;

class ValueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Value::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'model_type'      => $this->faker->word,
            'model_id'        => $this->faker->randomNumber(),
            'custom_field_id' => null,
            'integer'         => null,
            'float'           => null,
            'date'            => null,
            'text'            => null,
            'boolean'         => null,
            'string'          => null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ];
    }
}
