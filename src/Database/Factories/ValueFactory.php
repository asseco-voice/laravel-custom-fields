<?php

namespace Voice\CustomFields\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Voice\CustomFields\App\Value;

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
            'model_type'      => null,
            'model_id'        => null,
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
