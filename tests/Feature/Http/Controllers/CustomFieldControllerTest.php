<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Feature\Http\Controllers;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\Tests\TestCase;

class CustomFieldControllerTest extends TestCase
{
    protected CustomField $customField;

    public function setUp(): void
    {
        parent::setUp();

        $this->customField = app(CustomField::class);
    }

    /** @test */
    public function can_fetch_all_custom_fields()
    {
        $this
            ->getJson(route('custom-fields.index'))
            ->assertJsonCount(0);

        $this->customField::factory()->count(5)->create();

        $this
            ->getJson(route('custom-fields.index'))
            ->assertJsonCount(5);

        $this->assertCount(5, $this->customField::all());
    }

    /** @test */
    public function rejects_creating_custom_field_with_invalid_name()
    {
        $request = $this->customField::factory()->make([
            'name' => 'invalid name',
        ])->toArray();

        $this
            ->postJson(route('custom-fields.store'), $request)
            ->assertStatus(422);
    }

    /** @test */
    public function creates_custom_field()
    {
        $request = $this->customField::factory()->make()->toArray();

        $this
            ->postJson(route('custom-fields.store'), $request)
            ->assertJsonFragment([
                'name' => $request['name'],
            ]);

        $this->assertCount(1, $this->customField::all());
    }

    /** @test */
    public function can_return_custom_field_by_id()
    {
        $customFields = $this->customField::factory()->count(5)->create();

        $customFieldId = $customFields->random()->id;

        $this
            ->getJson(route('custom-fields.show', $customFieldId))
            ->assertJsonFragment(['id' => $customFieldId]);
    }

    /** @test */
    public function can_update_custom_field()
    {
        $customField = $this->customField::factory()->create();

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
        $customField = $this->customField::factory()->create();

        $this->assertCount(1, $this->customField::all());

        $this
            ->deleteJson(route('custom-fields.destroy', $customField->id))
            ->assertOk();

        $this->assertCount(0, $this->customField::all());
    }
}
