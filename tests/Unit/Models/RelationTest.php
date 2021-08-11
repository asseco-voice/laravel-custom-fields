<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Models\Relation;
use Asseco\CustomFields\Database\Factories\RelationFactory;
use Asseco\CustomFields\Tests\TestCase;

class RelationTest extends TestCase
{
    protected Relation $relation;

    public function setUp(): void
    {
        parent::setUp();

        $this->relation = app(Relation::class);
    }

    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(RelationFactory::class, $this->relation::factory());
    }
}
