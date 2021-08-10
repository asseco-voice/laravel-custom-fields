<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Feature\Http\Controllers;

use Asseco\CustomFields\App\Contracts\Validation;
use Asseco\CustomFields\Tests\TestCase;

class ValidationControllerTest extends TestCase
{
    protected Validation $validation;

    public function setUp(): void
    {
        parent::setUp();

        $this->validation = app(Validation::class);
    }

    /** @test */
    public function can_fetch_all_validations()
    {
        $this
            ->getJson(route('custom-field.validations.index'))
            ->assertJsonCount(0);

        $this->validation::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.validations.index'))
            ->assertJsonCount(5);

        $this->assertCount(5, $this->validation::all());
    }

    /** @test */
    public function creates_validation()
    {
        $request = $this->validation::factory()->make()->toArray();

        $this
            ->postJson(route('custom-field.validations.store'), $request)
            ->assertJsonFragment([
                'name' => $request['name'],
            ]);

        $this->assertCount(1, $this->validation::all());
    }

    /** @test */
    public function can_return_validation_by_id()
    {
        $validations = $this->validation::factory()->count(5)->create();

        $validationId = $validations->random()->id;

        $this
            ->getJson(route('custom-field.validations.show', $validationId))
            ->assertJsonFragment(['id' => $validationId]);
    }

    /** @test */
    public function can_update_validation()
    {
        $validation = $this->validation::factory()->create();

        $request = [
            'name' => 'updated_name',
        ];

        $this
            ->putJson(route('custom-field.validations.update', $validation->id), $request)
            ->assertJsonFragment([
                'name' => $request['name'],
            ]);

        $this->assertEquals($request['name'], $validation->refresh()->name);
    }

    /** @test */
    public function can_delete_validation()
    {
        $validation = $this->validation::factory()->create();

        $this->assertCount(1, $this->validation::all());

        $this
            ->deleteJson(route('custom-field.validations.destroy', $validation->id))
            ->assertOk();

        $this->assertCount(0, $this->validation::all());
    }
}
