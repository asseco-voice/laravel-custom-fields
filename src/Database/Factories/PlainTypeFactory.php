<?php

namespace Asseco\CustomFields\Database\Factories;

use Asseco\CustomFields\App\Models\PlainType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlainTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PlainType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'       => $this->faker->word,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
