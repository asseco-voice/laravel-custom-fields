<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ValueFactory extends Factory
{
    public function modelName()
    {
        return config('asseco-custom-fields.models.value');
    }

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
            'custom_field_id' => $this->faker->randomNumber(),
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
