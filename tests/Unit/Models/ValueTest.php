<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\Value;
use Asseco\CustomFields\Database\Factories\ValueFactory;
use Asseco\CustomFields\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ValueTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(ValueFactory::class, Value::factory());
    }

    /** @test */
    public function can_fetch_related_custom_field()
    {
        $customField = CustomField::factory()->create();

        $value = Value::factory()->create([
            'custom_field_id' => $customField->id,
        ]);

        $this->assertEquals($customField->id, $value->customField->id);
    }

    /** @test */
    public function returns_value_from_non_empty_column()
    {
        $value = Value::factory()->make([
            'string' => '123',
        ]);

        $this->assertEquals('123', $value->value);
    }

    /** @test */
    public function fails_to_return_if_all_value_columns_are_empty()
    {
        $value = Value::factory()->make();

        $this->assertNull($value->value);
    }

    /** @test */
    public function returns_only_first_match_of_many_non_empty_columns_by_order_of_precedence()
    {
        $value = Value::factory()->make([
            'integer' => 321,
            'string'  => 'something',
        ]);

        // 'string' is defined before 'integer' in Value::VALUE_COLUMNS
        $this->assertEquals('something', $value->value);
    }
}
