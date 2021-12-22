<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Feature\Http\Controllers;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\Contracts\Value;
use Asseco\CustomFields\App\PlainTypes\StringType;
use Asseco\CustomFields\Tests\TestCase;

class ValueControllerTest extends TestCase
{
    protected CustomField $customField;
    protected PlainType $plainType;
    protected Value $value;

    public function setUp(): void
    {
        parent::setUp();

        $this->customField = app(CustomField::class);
        $this->plainType = app(PlainType::class);
        $this->value = app(Value::class);
    }

    /** @test */
    public function can_fetch_all_values()
    {
        $this
            ->getJson(route('custom-field.values.index'))
            ->assertJsonCount(0);

        $customField = $this->customField::factory()->create();

        $this->value::factory()->count(5)->create(['custom_field_id'=> $customField->id]);

        $this
            ->getJson(route('custom-field.values.index'))
            ->assertJsonCount(5);

        $this->assertCount(5, $this->value::all());
    }

    /** @test */
    public function creates_value()
    {
        $selectable = $this->plainType::query()->where('name', 'string')->first();
        $customField = $this->customField::factory()->create([
            'selectable_type' => StringType::class,
            'selectable_id'   => $selectable->id,
        ]);

        $request = $this->value::factory()->make([
            'custom_field_id' => $customField->id,
            'string'          => 'test value',
        ])->toArray();

        $this
            ->postJson(route('custom-field.values.store'), $request)
            ->assertJsonFragment([
                'string' => $request['string'],
            ]);

        $this->assertCount(1, $this->value::all());
    }

    /** @test */
    public function fails_creating_if_value_has_no_valid_custom_field_relation()
    {
        // This way, CustomField ID will be random, not existing
        $request = $this->value::factory()->make()->toArray();

        $this
            ->postJson(route('custom-field.values.store'), $request)
            ->assertStatus(422);

        $this->assertCount(0, $this->value::all());
    }

    /** @test */
    public function fails_creating_if_value_has_inadequate_value_types()
    {
        $selectable = $this->plainType::query()->where('name', 'string')->first();
        $customField = $this->customField::factory()->create([
            'selectable_type' => StringType::class,
            'selectable_id'   => $selectable->id,
        ]);

        // Value is defined as 'string', so no other types should be provided
        $request = $this->value::factory()->make([
            'custom_field_id' => $customField->id,
            'string'          => 'test value',
            'text'            => 'should not be provided',
        ])->toArray();

        $this
            ->postJson(route('custom-field.values.store'), $request)
            ->assertStatus(500);

        $this->assertCount(0, $this->value::all());
    }

    /** @test */
    public function fails_creating_if_value_has_missing_value_types()
    {
        $selectable = $this->plainType::query()->where('name', 'string')->first();
        $customField = $this->customField::factory()->create([
            'selectable_type' => StringType::class,
            'selectable_id'   => $selectable->id,
        ]);

        // 'string' is required, but missing
        $request = $this->value::factory()->make([
            'custom_field_id' => $customField->id,
        ])->toArray();

        $this
            ->postJson(route('custom-field.values.store'), $request)
            ->assertStatus(500);

        $this->assertCount(0, $this->value::all());
    }

    /** @test */
    public function can_return_value_by_id()
    {
        $customField = $this->customField::factory()->create();

        $this->value::factory()->count(5)->create(['custom_field_id'=> $customField->id]);

        $this
            ->getJson(route('custom-field.values.show', 3))
            ->assertJsonFragment(['id' => 3]);
    }

    /** @test */
    public function can_update_value()
    {
        $selectable = $this->plainType::query()->where('name', 'string')->first();
        $customField = $this->customField::factory()->create([
            'selectable_type' => StringType::class,
            'selectable_id'   => $selectable->id,
        ]);

        $value = $this->value::factory()->create([
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
        $selectable = $this->plainType::query()->where('name', 'string')->first();
        $customField = $this->customField::factory()->create([
            'selectable_type' => StringType::class,
            'selectable_id'   => $selectable->id,
        ]);

        $value = $this->value::factory()->create([
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
        $customField = $this->customField::factory()->create();

        $value = $this->value::factory()->create(['custom_field_id'=> $customField->id]);

        $this->assertCount(1, $this->value::all());

        $this
            ->deleteJson(route('custom-field.values.destroy', $value->id))
            ->assertOk();

        $this->assertCount(0, $this->value::all());
    }
}
