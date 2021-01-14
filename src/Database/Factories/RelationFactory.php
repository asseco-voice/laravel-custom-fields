<?php

namespace Asseco\CustomFields\Database\Factories;

use Asseco\CustomFields\App\Models\Relation;
use Illuminate\Database\Eloquent\Factories\Factory;

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
