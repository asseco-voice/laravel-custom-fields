<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Factories;

use Asseco\CustomFields\App\Models\Validation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ValidationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Validation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'       => implode(' ', $this->faker->words(5)),
            'regex'      => 'some_regex',
            'generic'    => $this->faker->boolean(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
