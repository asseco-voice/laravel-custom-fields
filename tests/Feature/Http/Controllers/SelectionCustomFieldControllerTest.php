<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Feature\Http\Controllers;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\Contracts\SelectionType;
use Asseco\CustomFields\App\Contracts\SelectionValue;
use Asseco\CustomFields\Tests\TestCase;

class SelectionCustomFieldControllerTest extends TestCase
{
    protected CustomField $customField;
    protected PlainType $plainType;
    protected SelectionType $selectionType;
    protected SelectionValue $selectionValue;

    public function setUp(): void
    {
        parent::setUp();

        $this->customField = app(CustomField::class);
        $this->plainType = app(PlainType::class);
        $this->selectionType = app(SelectionType::class);
        $this->selectionValue = app(SelectionValue::class);
    }

    /** @test */
    public function returns_only_selection_custom_fields()
    {
        $this
            ->getJson(route('custom-field.selection.index'))
            ->assertJsonCount(0);

        $selectionType = $this->selectionType::factory()->create([
            'plain_type_id' => $this->plainType::query()->where('name', 'string')->first()->id,
        ]);

        $this->customField::factory()->create([
            'selectable_type' => get_class($this->selectionType),
            'selectable_id' => $selectionType->id,
        ]);

        $this->customField::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.selection.index'))
            ->assertJsonCount(1);

        $this->assertCount(6, $this->customField::all());
    }

    /** @test */
    public function rejects_creating_selection_custom_field_with_invalid_name()
    {
        $request = $this->customField::factory()->make([
            'name' => 'invalid name',
        ])->toArray();

        $this
            ->postJson(route('custom-field.selection.store', 'string'), $request)
            ->assertStatus(422);
    }

    /** @test */
    public function creates_selection_custom_field()
    {
        $request = $this->customField::factory()->make()->toArray();

        $request['selection'] = $this->selectionType::factory()->make([
            'plain_type_id' => $this->plainType::query()->where('name', 'string')->first()->id,
        ])->toArray();

        $this
            ->postJson(route('custom-field.selection.store', 'string'), $request)
            ->assertJsonFragment([
                'id' => 1,
                'name' => $request['name'],
            ]);

        $this->assertCount(1, $this->customField::all());
    }

    /** @test */
    public function creates_selection_custom_field_with_values()
    {
        $request = $this->customField::factory()->make()->toArray();

        $request['selection'] = $this->selectionType::factory()->make([
            'plain_type_id' => $this->plainType::query()->where('name', 'string')->first()->id,
        ])->toArray();

        // TODO: flaky test because 'value' is not unique in factory. If set to unique, it breaks seeders
        $request['values'] = $this->selectionValue::factory()->count(5)->make()->toArray();

        $this
            ->postJson(route('custom-field.selection.store', 'string'), $request)
            ->assertJsonFragment([
                'name' => $request['name'],
            ]);

        $this->assertCount(1, $this->customField::all());
        $this->assertCount(5, $this->selectionValue::all());
    }
}
