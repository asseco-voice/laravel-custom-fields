<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RemoteTypeFactory extends Factory
{
    public function modelName()
    {
        return config('asseco-custom-fields.models.remote_type');
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'plain_type_id' => null,
            'url' => $this->faker->url,
            'method' => $this->faker->randomElement(['GET', 'POST', 'PUT']),
            'body' => '{"test":"test"}',
            'mappings' => json_encode(
                array_combine(
                    $this->faker->words(5),
                    $this->faker->words(5),
                )),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
