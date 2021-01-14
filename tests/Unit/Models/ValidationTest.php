<?php

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Models\Validation;
use Asseco\CustomFields\Database\Factories\ValidationFactory;
use Asseco\CustomFields\Tests\TestCase;

class ValidationTest extends TestCase
{
    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(ValidationFactory::class, Validation::factory());
    }
}
