<?php

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Models\SelectionType;
use Asseco\CustomFields\Database\Factories\SelectionTypeFactory;
use Asseco\CustomFields\Tests\TestCase;

class SelectionTypeTest extends TestCase
{
    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(SelectionTypeFactory::class, SelectionType::factory());
    }
}
