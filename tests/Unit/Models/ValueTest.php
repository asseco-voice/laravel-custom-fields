<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\Value;
use Asseco\CustomFields\Database\Factories\ValueFactory;
use Asseco\CustomFields\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ValueTest extends TestCase
{
    use DatabaseMigrations;

    protected CustomField $customField;
    protected Value $value;

    public function setUp(): void
    {
        parent::setUp();

        $this->customField = app(CustomField::class);
        $this->value = app(Value::class);
    }

    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(ValueFactory::class, $this->value::factory());
    }

    /** @test */
    public function can_fetch_related_custom_field()
    {
        $customField = $this->customField::factory()->create();

        $value = $this->value::factory()->create([
            'custom_field_id' => $customField->id,
        ]);

        $this->assertEquals($customField->id, $value->customField->id);
    }

    /** @test */
    public function returns_value_from_non_empty_column()
    {
        $value = $this->value::factory()->make([
            'string' => '123',
        ]);

        $this->assertEquals('123', $value->value);
    }

    /** @test */
    public function fails_to_return_if_all_value_columns_are_empty()
    {
        $value = $this->value::factory()->make();

        $this->assertNull($value->value);
    }

    /** @test */
    public function returns_only_first_match_of_many_non_empty_columns_by_order_of_precedence()
    {
        $value = $this->value::factory()->make([
            'integer' => 321,
            'string'  => 'something',
        ]);

        // 'string' is defined before 'integer' in $this->value::VALUE_COLUMNS
        $this->assertEquals('something', $value->value);
    }
}
