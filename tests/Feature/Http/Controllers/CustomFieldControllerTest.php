<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Feature\Http\Controllers;

use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\Tests\TestCase;

class CustomFieldControllerTest extends TestCase
{
    /** @test */
    public function can_fetch_all_custom_fields()
    {
        $this
            ->getJson(route('custom-fields.index'))
            ->assertJsonCount(0);

        CustomField::factory()->count(5)->create();

        $this
            ->getJson(route('custom-fields.index'))
            ->assertJsonCount(5);

        $this->assertCount(5, CustomField::all());
    }

    /** @test */
    public function rejects_creating_custom_field_with_invalid_name()
    {
        $request = CustomField::factory()->make([
            'name' => 'invalid name',
        ])->toArray();

        $this
            ->postJson(route('custom-fields.store'), $request)
            ->assertStatus(422);
    }

    /** @test */
    public function creates_custom_field()
    {
        $request = CustomField::factory()->make()->toArray();

        $this
            ->postJson(route('custom-fields.store'), $request)
            ->assertJsonFragment([
                'id'   => 1,
                'name' => $request['name'],
            ]);

        $this->assertCount(1, CustomField::all());
    }

    /** @test */
    public function can_return_custom_field_by_id()
    {
        CustomField::factory()->count(5)->create();

        $this
            ->getJson(route('custom-fields.show', 3))
            ->assertJsonFragment(['id' => 3]);
    }

    /** @test */
    public function can_update_custom_field()
    {
        $customField = CustomField::factory()->create();

        $request = [
            'name' => 'updated_name',
        ];

        $this
            ->putJson(route('custom-fields.update', $customField->id), $request)
            ->assertJsonFragment([
                'name' => $request['name'],
            ]);

        $this->assertEquals($request['name'], $customField->refresh()->name);
    }

    /** @test */
    public function can_delete_custom_field()
    {
        $customField = CustomField::factory()->create();

        $this->assertCount(1, CustomField::all());

        $this
            ->deleteJson(route('custom-fields.destroy', $customField->id))
            ->assertOk();

        $this->assertCount(0, CustomField::all());
    }
}
