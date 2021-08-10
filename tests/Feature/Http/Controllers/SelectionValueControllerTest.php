<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Feature\Http\Controllers;

use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\Contracts\SelectionType;
use Asseco\CustomFields\App\Contracts\SelectionValue;
use Asseco\CustomFields\Tests\TestCase;

class SelectionValueControllerTest extends TestCase
{
    protected PlainType $plainType;
    protected SelectionType $selectionType;
    protected SelectionValue $selectionValue;

    public function setUp(): void
    {
        parent::setUp();

        $this->plainType = app(PlainType::class);
        $this->selectionType = app(SelectionType::class);
        $this->selectionValue = app(SelectionValue::class);
    }

    /** @test */
    public function can_fetch_all_selection_values()
    {
        $this
            ->getJson(route('custom-field.selection-values.index'))
            ->assertJsonCount(0);

        $this->selectionValue::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.selection-values.index'))
            ->assertJsonCount(5);

        $this->assertCount(5, $this->selectionValue::all());
    }

    /** @test */
    public function creates_selection_value()
    {
        $plain = $this->plainType::factory()->create();

        $type = $this->selectionType::factory()->create([
            'plain_type_id' => $plain->id,
        ]);

        $request = $this->selectionValue::factory()->make([
            'selection_type_id' => $type->id,
        ])->toArray();

        $this
            ->postJson(route('custom-field.selection-values.store'), $request)
            ->assertJsonFragment([
                'label' => $request['label'],
            ]);

        $this->assertCount(1, $this->selectionValue::all());
    }

    /** @test */
    public function can_return_selection_value_by_id()
    {
        $selectionValues = $this->selectionValue::factory()->count(5)->create();

        $selectionValueId = $selectionValues->random()->id;

        $this
            ->getJson(route('custom-field.selection-values.show', $selectionValueId))
            ->assertJsonFragment(['id' => $selectionValueId]);
    }

    /** @test */
    public function can_update_selection_value()
    {
        $plain = $this->plainType::factory()->create();

        $type = $this->selectionType::factory()->create([
            'plain_type_id' => $plain->id,
        ]);

        $selectionValue = $this->selectionValue::factory()->create([
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
        $selectionValue = $this->selectionValue::factory()->create();

        $this->assertCount(1, $this->selectionValue::all());

        $this
            ->deleteJson(route('custom-field.selection-values.destroy', $selectionValue->id))
            ->assertOk();

        $this->assertCount(0, $this->selectionValue::all());
    }
}
