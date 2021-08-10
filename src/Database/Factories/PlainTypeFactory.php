<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Factories;

use Asseco\CustomFields\App\Models\PlainType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlainTypeFactory extends Factory
{
    public function modelName()
    {
        return config('asseco-custom-fields.models.plain_type');
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'       => implode(' ', $this->faker->words(5)),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
