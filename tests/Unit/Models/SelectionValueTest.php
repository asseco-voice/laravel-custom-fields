<?php

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Models\SelectionValue;
use Asseco\CustomFields\Database\Factories\SelectionValueFactory;
use Asseco\CustomFields\Tests\TestCase;

class SelectionValueTest extends TestCase
{
    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(SelectionValueFactory::class, SelectionValue::factory());
    }
}
