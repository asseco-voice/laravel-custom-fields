<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Models\PlainType;
use Asseco\CustomFields\App\Models\SelectionType;
use Asseco\CustomFields\App\Models\SelectionValue;
use Asseco\CustomFields\Database\Factories\SelectionValueFactory;
use Asseco\CustomFields\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SelectionValueTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(SelectionValueFactory::class, SelectionValue::factory());
    }

    /** @test */
    public function can_fetch_selection_type()
    {
        $selectionType = SelectionType::factory()->create([
            'plain_type_id' => PlainType::factory()->create()->id,
        ]);

        $selectionValue = SelectionValue::factory()->create([
            'selection_type_id' => $selectionType->id,
        ]);

        $this->assertEquals($selectionValue->id, $selectionType->values->first()->id);
    }
}
