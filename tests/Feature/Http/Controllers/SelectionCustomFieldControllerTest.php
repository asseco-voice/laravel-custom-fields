<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Feature\Http\Controllers;

use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\PlainType;
use Asseco\CustomFields\App\Models\SelectionType;
use Asseco\CustomFields\App\Models\SelectionValue;
use Asseco\CustomFields\Tests\TestCase;

class SelectionCustomFieldControllerTest extends TestCase
{
    /** @test */
    public function returns_only_selection_custom_fields()
    {
        $this
            ->getJson(route('custom-field.selection.index'))
            ->assertJsonCount(0);

        $selectionType = SelectionType::factory()->create([
            'plain_type_id' => PlainType::factory()->create(['name' => 'string'])->id,
        ]);

        CustomField::factory()->create([
            'selectable_type' => SelectionType::class,
            'selectable_id'   => $selectionType->id,
        ]);

        CustomField::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.selection.index'))
            ->assertJsonCount(1);

        $this->assertCount(6, CustomField::all());
    }

    /** @test */
    public function rejects_creating_selection_custom_field_with_invalid_name()
    {
        $request = CustomField::factory()->make([
            'name' => 'invalid name',
        ])->toArray();

        $this
            ->postJson(route('custom-field.selection.store', 'string'), $request)
            ->assertStatus(422);
    }

    /** @test */
    public function rejects_creating_selection_custom_field_without_selection_parameters()
    {
        $request = CustomField::factory()->make()->toArray();

        $this
            ->postJson(route('custom-field.selection.store', 'string'), $request)
            ->assertStatus(500);
    }

    /** @test */
    public function creates_selection_custom_field()
    {
        $request = CustomField::factory()->make()->toArray();

        $request['selection'] = SelectionType::factory()->make([
            'plain_type_id' => PlainType::factory()->create(['name' => 'string'])->id,
        ])->toArray();

        $this
            ->postJson(route('custom-field.selection.store', 'string'), $request)
            ->assertJsonFragment([
                'id'   => 1,
                'name' => $request['name'],
            ]);

        $this->assertCount(1, CustomField::all());
    }

    /** @test */
    public function creates_selection_custom_field_with_values()
    {
        $request = CustomField::factory()->make()->toArray();

        $request['selection'] = SelectionType::factory()->make([
            'plain_type_id' => PlainType::factory()->create(['name' => 'string'])->id,
        ])->toArray();

        $request['values'] = SelectionValue::factory()->count(5)->make()->toArray();

        $this
            ->postJson(route('custom-field.selection.store', 'string'), $request)
            ->assertJsonFragment([
                'id'   => 1,
                'name' => $request['name'],
            ]);

        $this->assertCount(1, CustomField::all());
        $this->assertCount(5, SelectionValue::all());
    }
}
