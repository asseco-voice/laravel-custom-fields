<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Feature\Http\Controllers;

use Asseco\CustomFields\App\Models\PlainType;
use Asseco\CustomFields\App\Models\SelectionType;
use Asseco\CustomFields\App\Models\SelectionValue;
use Asseco\CustomFields\Tests\TestCase;

class SelectionValueControllerTest extends TestCase
{
    /** @test */
    public function can_fetch_all_selection_values()
    {
        $this
            ->getJson(route('custom-field.selection-values.index'))
            ->assertJsonCount(0);

        SelectionValue::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.selection-values.index'))
            ->assertJsonCount(5);

        $this->assertCount(5, SelectionValue::all());
    }

    /** @test */
    public function creates_selection_value()
    {
        $plain = PlainType::factory()->create();

        $type = SelectionType::factory()->create([
            'plain_type_id' => $plain->id,
        ]);

        $request = SelectionValue::factory()->make([
            'selection_type_id' => $type->id,
        ])->toArray();

        $this
            ->postJson(route('custom-field.selection-values.store'), $request)
            ->assertJsonFragment([
                'label' => $request['label'],
            ]);

        $this->assertCount(1, SelectionValue::all());
    }

    /** @test */
    public function can_return_selection_value_by_id()
    {
        $selectionValues = SelectionValue::factory()->count(5)->create();

        $selectionValueId = $selectionValues->random()->id;

        $this
            ->getJson(route('custom-field.selection-values.show', $selectionValueId))
            ->assertJsonFragment(['id' => $selectionValueId]);
    }

    /** @test */
    public function can_update_selection_value()
    {
        $plain = PlainType::factory()->create();

        $type = SelectionType::factory()->create([
            'plain_type_id' => $plain->id,
        ]);

        $selectionValue = SelectionValue::factory()->create([
            'selection_type_id' => $type->id,
        ]);

        $request = [
            'label' => 'updated_label',
        ];

        $this
            ->putJson(route('custom-field.selection-values.update', $selectionValue->id), $request)
            ->assertJsonFragment([
                'label' => $request['label'],
            ]);

        $this->assertEquals($request['label'], $selectionValue->refresh()->label);
    }

    /** @test */
    public function can_delete_selection_value()
    {
        $selectionValue = SelectionValue::factory()->create();

        $this->assertCount(1, SelectionValue::all());

        $this
            ->deleteJson(route('custom-field.selection-values.destroy', $selectionValue->id))
            ->assertOk();

        $this->assertCount(0, SelectionValue::all());
    }
}
