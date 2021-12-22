<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\Contracts\Relation;
use Asseco\CustomFields\App\Contracts\RemoteType;
use Asseco\CustomFields\App\Contracts\SelectionType;
use Asseco\CustomFields\App\Contracts\Value;
use Asseco\CustomFields\App\PlainTypes\BooleanType;
use Asseco\CustomFields\App\PlainTypes\DateType;
use Asseco\CustomFields\App\PlainTypes\FloatType;
use Asseco\CustomFields\App\PlainTypes\IntegerType;
use Asseco\CustomFields\App\PlainTypes\StringType;
use Asseco\CustomFields\App\PlainTypes\TextType;
use Asseco\CustomFields\Database\Factories\CustomFieldFactory;
use Asseco\CustomFields\Tests\TestCase;
use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CustomFieldTest extends TestCase
{
    use DatabaseMigrations;

    protected CustomField $customField;
    protected PlainType $plainType;
    protected Relation $relation;
    protected RemoteType $remoteType;
    protected SelectionType $selectionType;
    protected Value $value;

    public function setUp(): void
    {
        parent::setUp();

        $this->customField = app(CustomField::class);
        $this->plainType = app(PlainType::class);
        $this->relation = app(Relation::class);
        $this->remoteType = app(RemoteType::class);
        $this->selectionType = app(SelectionType::class);
        $this->value = app(Value::class);
    }

    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(CustomFieldFactory::class, $this->customField::factory());
    }

    /** @test */
    public function accepts_valid_custom_field_names()
    {
        $this->customField::factory()->create(['name' => 'valid_name']);

        $this->assertTrue(true);
    }

    /** @test */
    public function rejects_custom_field_name_containing_spaces()
    {
        $this->expectException(Exception::class);

        $this->customField::factory()->create(['name' => 'not a valid name']);
    }

    /** @test */
    public function filters_out_plain_types()
    {
        $plainType1 = $this->plainType::query()->where('name', 'string')->first();
        $plainType2 = $this->plainType::query()->where('name', 'boolean')->first();

        $this->customField::factory()->create([
            'selectable_type' => StringType::class,
            'selectable_id'   => $plainType1->id,
        ]);
        $this->customField::factory()->create([
            'selectable_type' => BooleanType::class,
            'selectable_id'   => $plainType2->id,
        ]);

        $this->customField::factory()->create(['selectable_type' => 'NotA$this->plainType::class']);

        $this->assertEquals(2, $this->customField::plain()->get()->count());
    }

    /** @test */
    public function filters_out_remote_types()
    {
        $plainType1 = $this->plainType::query()->where('name', 'string')->first();

        $this->remoteType::factory()->count(2)->create([
            'plain_type_id' => $plainType1->id,
        ]);

        $this->customField::factory()->create(['selectable_type' => get_class($this->remoteType), 'selectable_id' => 1]);
        $this->customField::factory()->create(['selectable_type' => get_class($this->remoteType), 'selectable_id' => 2]);

        $this->customField::factory()->create(['selectable_type' => 'NotA$this->remoteType::class']);

        $this->assertEquals(2, $this->customField::remote()->get()->count());
    }

    /** @test */
    public function filters_out_selection_types()
    {
        $plainType1 = $this->plainType::query()->where('name', 'string')->first();

        $this->selectionType::factory()->count(2)->create([
            'plain_type_id' => $plainType1->id,
        ]);

        $this->customField::factory()->create(['selectable_type' => get_class($this->selectionType), 'selectable_id' => 1]);
        $this->customField::factory()->create(['selectable_type' => get_class($this->selectionType), 'selectable_id' => 2]);

        $this->customField::factory()->create(['selectable_type' => 'NotASelectionType::class']);

        $this->assertEquals(2, $this->customField::selection()->get()->count());
    }

    /** @test */
    public function will_not_validate_if_validation_not_present()
    {
        $customField1 = $this->customField::factory()->create();

        $customField1->validate(['test' => 'test']);

        $this->assertTrue(true);
    }

    /** @test */
    public function has_child_relations()
    {
        $this->customField::factory()->count(5)->create();

        /**
         * @var $customField CustomField
         */
        $customField = $this->customField::query()->first();

        $customField->children()->attach([2, 3, 4, 5]);

        $this->assertEquals(4, $customField->children->count());
        $this->assertEquals(4, $this->relation::all()->count());
    }

    /** @test */
    public function has_parent_relations()
    {
        $this->customField::factory()->count(5)->create();

        /**
         * @var $customField CustomField
         */
        $customField = $this->customField::query()->first();

        $customField->parent()->attach([2, 3, 4, 5]);

        $this->assertEquals(4, $customField->parent->count());
        $this->assertEquals(4, $this->relation::all()->count());
    }

    /** @test */
    public function flattens_registered_types()
    {
        $types = $this->customField::types();

        $this->assertArrayHasKey('string', $types);
        $this->assertArrayHasKey('integer', $types);
        $this->assertArrayHasKey('float', $types);
        $this->assertArrayHasKey('date', $types);
        $this->assertArrayHasKey('text', $types);
        $this->assertArrayHasKey('boolean', $types);
        $this->assertArrayHasKey('remote', $types);
        $this->assertArrayHasKey('selection', $types);
    }

    /** @test */
    public function returns_appropriate_value_column()
    {
        $plainType1 = $this->plainType::query()->where('name', 'string')->first();
        $plainType2 = $this->plainType::query()->where('name', 'integer')->first();
        $plainType3 = $this->plainType::query()->where('name', 'float')->first();
        $plainType4 = $this->plainType::query()->where('name', 'date')->first();
        $plainType5 = $this->plainType::query()->where('name', 'text')->first();
        $plainType6 = $this->plainType::query()->where('name', 'boolean')->first();

        // Plain types

        /**
         * @var $customField1 CustomField
         */
        $customField1 = $this->customField::factory()->create([
            'selectable_type' => StringType::class,
            'selectable_id'   => $plainType1->id,
        ]);

        $this->assertEquals('string', $customField1->getValueColumn());

        /**
         * @var $customField2 CustomField
         */
        $customField2 = $this->customField::factory()->create([
            'selectable_type' => IntegerType::class,
            'selectable_id'   => $plainType2->id,
        ]);

        $this->assertEquals('integer', $customField2->getValueColumn());

        /**
         * @var $customField3 CustomField
         */
        $customField3 = $this->customField::factory()->create([
            'selectable_type' => FloatType::class,
            'selectable_id'   => $plainType3->id,
        ]);

        $this->assertEquals('float', $customField3->getValueColumn());

        /**
         * @var $customField4 CustomField
         */
        $customField4 = $this->customField::factory()->create([
            'selectable_type' => DateType::class,
            'selectable_id'   => $plainType4->id,
        ]);

        $this->assertEquals('date', $customField4->getValueColumn());

        /**
         * @var $customField5 CustomField
         */
        $customField5 = $this->customField::factory()->create([
            'selectable_type' => TextType::class,
            'selectable_id'   => $plainType5->id,
        ]);

        $this->assertEquals('text', $customField5->getValueColumn());

        /**
         * @var $customField6 CustomField
         */
        $customField6 = $this->customField::factory()->create([
            'selectable_type' => BooleanType::class,
            'selectable_id'   => $plainType6->id,
        ]);

        $this->assertEquals('boolean', $customField6->getValueColumn());

        // Remote type
        $remoteType = $this->remoteType::factory()->create([
            'plain_type_id' => $plainType3->id,
        ]);

        /**
         * @var $customField7 CustomField
         */
        $customField7 = $this->customField::factory()->create([
            'selectable_type' => get_class($this->remoteType),
            'selectable_id'   => $remoteType->id,
        ]);

        $this->assertEquals('float', $customField7->getValueColumn());

        // Selection type
        $selectionType = $this->selectionType::factory()->create([
            'plain_type_id' => $plainType5->id,
        ]);

        /**
         * @var $customField8 CustomField
         */
        $customField8 = $this->customField::factory()->create([
            'selectable_type' => get_class($this->selectionType),
            'selectable_id'   => $selectionType->id,
        ]);

        $this->assertEquals('text', $customField8->getValueColumn());
    }

    /** @test */
    public function falls_back_to_string_value_column_for_unknown_types()
    {
        /**
         * @var $customField CustomField
         */
        $customField = $this->customField::factory()->create();

        $this->assertEquals('string', $customField->getValueColumn());
    }

    /** @test */
    public function provides_short_format()
    {
        $plainType = $this->plainType::query()->where('name', 'string')->first();

        /**
         * @var $customField CustomField
         */
        $customField = $this->customField::factory()->create([
            'name'            => 'cf1',
            'selectable_type' => StringType::class,
            'selectable_id'   => $plainType->id,
        ]);

        /**
         * @var $value Value
         */
        $value = $this->value::factory()->create([
            'custom_field_id' => $customField->id,
            'string'          => 'some value',
        ]);

        $expected = [
            'cf1' => [
                'type'  => 'string',
                'value' => 'some value',
            ],
        ];

        $this->assertEquals($expected, $customField->shortFormat($value->value));
    }

    /** @test */
    public function short_format_returns_fallback_for_non_existing_selectable_types()
    {
        /**
         * @var $customField CustomField
         */
        $customField = $this->customField::factory()->create(['name' => 'cf1']);

        /**
         * @var $value Value
         */
        $value = $this->value::factory()->create([
            'custom_field_id' => $customField->id,
            'string'          => 'some value',
        ]);

        $expected = [
            'cf1' => [
                'type'  => 'string',
                'value' => 'some value',
            ],
        ];

        $this->assertEquals($expected, $customField->shortFormat($value->value));
    }
}
