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
            'method'        => $this->faker->randomElement(['GET', 'POST', 'PUT']),
            'body'          => '{"test":"test"}',
            'mappings'      => json_encode(
                array_combine(
                    $this->faker->words(5),
                    $this->faker->words(5),
                )),
            'created_at'    => now(),
            'updated_at'    => now(),
        ];
    }
}
