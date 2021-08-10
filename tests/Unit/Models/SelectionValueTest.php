<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\Contracts\SelectionType;
use Asseco\CustomFields\App\Contracts\SelectionValue;
use Asseco\CustomFields\Database\Factories\SelectionValueFactory;
use Asseco\CustomFields\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SelectionValueTest extends TestCase
{
    use DatabaseMigrations;

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
    public function has_factory()
    {
        $this->assertInstanceOf(SelectionValueFactory::class, $this->selectionValue::factory());
    }

    /** @test */
    public function can_fetch_selection_type()
    {
        $selectionType = $this->selectionType::factory()->create([
            'plain_type_id' => $this->plainType::factory()->create()->id,
        ]);

        $selectionValue = $this->selectionValue::factory()->create([
            'selection_type_id' => $selectionType->id,
        ]);

        $this->assertEquals($selectionValue->id, $selectionType->values->first()->id);
    }
}
