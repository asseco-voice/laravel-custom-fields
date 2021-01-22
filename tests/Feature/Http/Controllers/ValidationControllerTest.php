<?php

declare(strict_types=1);

namespace Asseco\Validations\Tests\Feature;

use Asseco\CustomFields\App\Models\Validation;
use Asseco\CustomFields\Tests\TestCase;

class ValidationControllerTest extends TestCase
{
    /** @test */
    public function can_fetch_all_validations()
    {
        $this
            ->getJson(route('custom-field.validations.index'))
            ->assertJsonCount(0);

        Validation::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.validations.index'))
            ->assertJsonCount(5);

        $this->assertCount(5, Validation::all());
    }

    /** @test */
    public function creates_validation()
    {
        $request = Validation::factory()->make()->toArray();

        $this
            ->postJson(route('custom-field.validations.store'), $request)
            ->assertJsonFragment([
                'id'   => 1,
                'name' => $request['name']
            ]);

        $this->assertCount(1, Validation::all());
    }

    /** @test */
    public function can_return_validation_by_id()
    {
        Validation::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.validations.show', 3))
            ->assertJsonFragment(['id' => 3]);
    }

    /** @test */
    public function can_update_validation()
    {
        $validation = Validation::factory()->create();

        $request = [
            'name' => 'updated_name',
        ];

        $this
            ->putJson(route('custom-field.validations.update', $validation->id), $request)
            ->assertJsonFragment([
                'name' => $request['name']
            ]);

        $this->assertEquals($request['name'], $validation->refresh()->name);
    }

    /** @test */
    public function can_delete_validation()
    {
        $validation = Validation::factory()->create();

        $this->assertCount(1, Validation::all());

        $this
            ->deleteJson(route('custom-field.validations.destroy', $validation->id))
            ->assertOk();

        $this->assertCount(0, Validation::all());
    }
}
