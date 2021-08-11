<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RelationFactory extends Factory
{
    public function modelName()
    {
        return config('asseco-custom-fields.models.relation');
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'parent_id'  => $this->faker->randomNumber(),
            'child_id'   => $this->faker->randomNumber(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
