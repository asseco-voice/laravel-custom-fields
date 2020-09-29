<?php

namespace Voice\CustomFields\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;
use Voice\CustomFields\App\CustomField;

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
            'selectable_type' => null,
            'selectable_id'   => null,
            'name'            => implode(' ', $this->faker->words(5)),
            'label'           => $this->faker->word,
            'placeholder'     => $this->faker->word,
            'model'           => null,
            'required'        => $this->faker->boolean(10),
            'validation_id'   => null,
            'created_at'      => Date::now(),
            'updated_at'      => Date::now(),
        ];
    }
}
