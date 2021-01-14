<?php

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\PlainType;
use Asseco\CustomFields\App\Models\SelectionType;
use Asseco\CustomFields\App\Models\SelectionValue;
use Asseco\CustomFields\Database\Factories\SelectionTypeFactory;
use Asseco\CustomFields\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SelectionTypeTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_fetch_related_custom_fields()
    {
        $selectionType = $this->createSelectionType();

        $customField = CustomField::factory()->create([
            'selectable_type' => SelectionType::class,
            'selectable_id'   => $selectionType->id,
        ]);

        $this->assertEquals($customField->id, $selectionType->customFields->first()->id);
    }

    /** @test */
    public function can_fetch_values()
    {
        $selectionType = $this->createSelectionType();

        $selectionValue = SelectionValue::factory()->create([
            'selection_type_id' => $selectionType->id,
        ]);

        $this->assertEquals($selectionValue->id, $selectionType->values->first()->id);
    }

    /** @test */
    public function appends_name_attribute()
    {
        $remoteType = SelectionType::factory()->make();

        $this->assertEquals('selection', $remoteType->name);
    }

    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(SelectionTypeFactory::class, SelectionType::factory());
    }

    protected function createSelectionType()
    {
        return SelectionType::factory()->create([
            'plain_type_id' => PlainType::factory()->create()->id,
        ]);
    }
}
