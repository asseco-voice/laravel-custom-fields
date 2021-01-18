<?php

namespace Asseco\CustomFields\Database\Factories;

use Asseco\CustomFields\App\Models\CustomField;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomFieldFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomField::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'selectable_type' => $this->faker->word,
            'selectable_id'   => $this->faker->randomNumber(),
            'name'            => implode('_', $this->faker->words(5)),
            'label'           => $this->faker->word,
            'placeholder'     => $this->faker->word,
            'model'           => $this->faker->word,
            'required'        => $this->faker->boolean(10),
            'validation_id'   => null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ];
    }
}
