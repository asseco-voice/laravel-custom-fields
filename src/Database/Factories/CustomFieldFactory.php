<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Factories;

use Asseco\CustomFields\App\Models\CustomField;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomFieldFactory extends Factory
{
    public function modelName()
    {
        return config('asseco-custom-fields.models.custom_field');
    }

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
            'group'           => null,
            'order'           => null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ];
    }
}
