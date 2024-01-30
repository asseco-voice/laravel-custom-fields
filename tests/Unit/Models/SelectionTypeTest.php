<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\Contracts\SelectionType;
use Asseco\CustomFields\App\Contracts\SelectionValue;
use Asseco\CustomFields\Database\Factories\SelectionTypeFactory;
use Asseco\CustomFields\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SelectionTypeTest extends TestCase
{
    use DatabaseMigrations;

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
    public function has_factory()
    {
        $this->assertInstanceOf(SelectionTypeFactory::class, $this->selectionType::factory());
    }

    /** @test */
    public function can_fetch_related_custom_fields()
    {
        $selectionType = $this->createSelectionType();

        $customField = $this->customField::factory()->create([
            'selectable_type' => get_class($this->selectionType),
            'selectable_id' => $selectionType->id,
        ]);

        $this->assertEquals($customField->id, $selectionType->customFields->first()->id);
    }

    /** @test */
    public function can_fetch_values()
    {
        $selectionType = $this->createSelectionType();

        $selectionValue = $this->selectionValue::factory()->create([
            'selection_type_id' => $selectionType->id,
        ]);

        $this->assertEquals($selectionValue->id, $selectionType->values->first()->id);
    }

    /** @test */
    public function appends_name_attribute()
    {
        $remoteType = $this->selectionType::factory()->make();

        $this->assertEquals('selection', $remoteType->name);
    }

    protected function createSelectionType()
    {
        return $this->selectionType::factory()->create([
            'plain_type_id' => $this->plainType::factory()->create()->id,
        ]);
    }
}
