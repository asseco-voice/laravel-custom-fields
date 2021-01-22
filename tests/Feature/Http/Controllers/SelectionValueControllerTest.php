<?php

declare(strict_types=1);

namespace Asseco\SelectionValues\Tests\Feature;

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
        $request = SelectionValue::factory()->make()->toArray();

        $this
            ->postJson(route('custom-field.selection-values.store'), $request)
            ->assertJsonFragment([
                'id'   => 1,
                'label' => $request['label']
            ]);

        $this->assertCount(1, SelectionValue::all());
    }

    /** @test */
    public function can_return_selection_value_by_id()
    {
        SelectionValue::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.selection-values.show', 3))
            ->assertJsonFragment(['id' => 3]);
    }

    /** @test */
    public function can_update_selection_value()
    {
        $selectionValue = SelectionValue::factory()->create();

        $request = [
            'label' => 'updated_label',
        ];

        $this
            ->putJson(route('custom-field.selection-values.update', $selectionValue->id), $request)
            ->assertJsonFragment([
                'label' => $request['label']
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
