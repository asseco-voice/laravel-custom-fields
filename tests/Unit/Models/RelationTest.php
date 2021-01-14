<?php

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Models\Relation;
use Asseco\CustomFields\Database\Factories\RelationFactory;
use Asseco\CustomFields\Tests\TestCase;

class RelationTest extends TestCase
{
    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(RelationFactory::class, Relation::factory());
    }
}
