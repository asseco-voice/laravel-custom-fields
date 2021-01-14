<?php

namespace Asseco\CustomFields\Database\Factories;

use Asseco\CustomFields\App\Models\RemoteType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RemoteTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RemoteType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'plain_type_id' => null,
            'url'           => $this->faker->url,
            'method'        => null,
            'body'          => $this->faker->sentence,
            'created_at'    => now(),
            'updated_at'    => now(),
        ];
    }
}
