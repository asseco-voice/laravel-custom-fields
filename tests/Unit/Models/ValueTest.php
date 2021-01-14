<?php

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Models\Value;
use Asseco\CustomFields\Database\Factories\ValueFactory;
use Asseco\CustomFields\Tests\TestCase;

class ValueTest extends TestCase
{
    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(ValueFactory::class, Value::factory());
    }
}
