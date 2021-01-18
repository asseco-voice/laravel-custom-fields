<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\Database\Factories\CustomFieldFactory;
use Asseco\CustomFields\Tests\TestCase;

class CustomFieldTest extends TestCase
{
    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(CustomFieldFactory::class, CustomField::factory());
    }
}
