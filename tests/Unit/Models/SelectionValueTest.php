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

    #[\PHPUnit\Framework\Attributes\Test]
    public function has_factory()
    {
        $this->assertInstanceOf(SelectionValueFactory::class, $this->selectionValue::factory());
    }

    #[\PHPUnit\Framework\Attributes\Test]
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

    #[\PHPUnit\Framework\Attributes\Test]
    public function boolean_values_read_back_as_stored()
    {
        $selectionType = $this->selectionType::factory()->create([
            'plain_type_id' => $this->plainType::query()->where('name', 'boolean')->firstOrFail()->id,
        ]);

        $expected = ['true' => true, 'false' => false, '1' => true, '0' => false, 'no' => false, 'maybe' => true];

        foreach ($expected as $stored => $read) {
            $selectionValue = $this->selectionValue::factory()->create([
                'selection_type_id' => $selectionType->id,
                'value'             => (string) $stored,
            ]);

            $this->assertSame($read, $selectionValue->fresh()->value, "stored \"$stored\"");
        }
    }
}
