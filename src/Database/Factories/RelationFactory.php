<?php

namespace Voice\CustomFields\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Voice\CustomFields\App\Relation;

class RelationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Relation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'parent_id'  => null,
            'child_id'   => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
