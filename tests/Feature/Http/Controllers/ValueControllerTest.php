<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Feature\Http\Controllers;

use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\PlainType;
use Asseco\CustomFields\App\Models\Value;
use Asseco\CustomFields\App\PlainTypes\StringType;
use Asseco\CustomFields\Tests\TestCase;

class ValueControllerTest extends TestCase
{
    /** @test */
    public function can_fetch_all_values()
    {
        $this
            ->getJson(route('custom-field.values.index'))
            ->assertJsonCount(0);

        Value::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.values.index'))
            ->assertJsonCount(5);

        $this->assertCount(5, Value::all());
    }

    /** @test */
    public function creates_value()
    {
        $selectable = PlainType::factory()->create(['name' => 'string']);
        $customField = CustomField::factory()->create([
            'selectable_type' => StringType::class,
            'selectable_id'   => $selectable->id,
        ]);

        $request = Value::factory()->make([
            'custom_field_id' => $customField->id,
            'string'          => 'test value',
        ])->toArray();

        $this
            ->postJson(route('custom-field.values.store'), $request)
            ->assertJsonFragment([
                'id'     => 1,
                'string' => $request['string'],
            ]);

        $this->assertCount(1, Value::all());
    }

    /** @test */
    public function fails_creating_if_value_has_no_valid_custom_field_relation()
    {
        // This way, CustomField ID will be random, not existing
        $request = Value::factory()->make()->toArray();

        $this
            ->postJson(route('custom-field.values.store'), $request)
            ->assertStatus(404);

        $this->assertCount(0, Value::all());
    }

    /** @test */
    public function fails_creating_if_value_has_inadequate_value_types()
    {
        $selectable = PlainType::factory()->create(['name' => 'string']);
        $customField = CustomField::factory()->create([
            'selectable_type' => StringType::class,
            'selectable_id'   => $selectable->id,
        ]);

        // Value is defined as 'string', so no other types should be provided
        $request = Value::factory()->make([
            'custom_field_id' => $customField->id,
            'string'          => 'test value',
            'text'            => 'should not be provided',
        ])->toArray();

        $this
            ->postJson(route('custom-field.values.store'), $request)
            ->assertStatus(500);

        $this->assertCount(0, Value::all());
    }

    /** @test */
    public function fails_creating_if_value_has_missing_value_types()
    {
        $selectable = PlainType::factory()->create(['name' => 'string']);
        $customField = CustomField::factory()->create([
            'selectable_type' => StringType::class,
            'selectable_id'   => $selectable->id,
        ]);

        // 'string' is required, but missing
        $request = Value::factory()->make([
            'custom_field_id' => $customField->id,
        ])->toArray();

        $this
            ->postJson(route('custom-field.values.store'), $request)
            ->assertStatus(500);

        $this->assertCount(0, Value::all());
    }

    /** @test */
    public function can_return_value_by_id()
    {
        Value::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.values.show', 3))
            ->assertJsonFragment(['id' => 3]);
    }

    /** @test */
    public function can_update_value()
    {
        $selectable = PlainType::factory()->create(['name' => 'string']);
        $customField = CustomField::factory()->create([
            'selectable_type' => StringType::class,
            'selectable_id'   => $selectable->id,
        ]);

        $value = Value::factory()->create([
            'custom_field_id' => $customField->id,
            'string'          => 'test value',
        ]);

        $request = [
            'string' => 'updated_value',
        ];

        $this
            ->putJson(route('custom-field.values.update', $value->id), $request)
            ->assertJsonFragment([
                'string' => $request['string'],
            ]);

        $this->assertEquals($request['string'], $value->refresh()->string);
    }

    /** @test */
    public function fails_updating_if_value_has_inadequate_value_types()
    {
        $selectable = PlainType::factory()->create(['name' => 'string']);
        $customField = CustomField::factory()->create([
            'selectable_type' => StringType::class,
            'selectable_id'   => $selectable->id,
        ]);

        $value = Value::factory()->create([
            'custom_field_id' => $customField->id,
            'string'          => 'test value',
        ]);

        $request = [
            'text' => 'should not be provided',
        ];

        $this
            ->putJson(route('custom-field.values.update', $value->id), $request)
            ->assertStatus(500);
    }

    /** @test */
    public function can_delete_value()
    {
        $value = Value::factory()->create();

        $this->assertCount(1, Value::all());

        $this
            ->deleteJson(route('custom-field.values.destroy', $value->id))
            ->assertOk();

        $this->assertCount(0, Value::all());
    }
}
