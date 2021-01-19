<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\PlainType;
use Asseco\CustomFields\App\Models\Relation;
use Asseco\CustomFields\App\Models\RemoteType;
use Asseco\CustomFields\App\Models\SelectionType;
use Asseco\CustomFields\App\Models\Value;
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

    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(CustomFieldFactory::class, CustomField::factory());
    }

    /** @test */
    public function accepts_valid_custom_field_names()
    {
        CustomField::factory()->create(['name' => 'valid_name']);

        $this->assertTrue(true);
    }

    /** @test */
    public function rejects_custom_field_name_containing_spaces()
    {
        $this->expectException(Exception::class);

        CustomField::factory()->create(['name' => 'not a valid name']);
    }

    /** @test */
    public function filters_out_plain_types()
    {
        $plainType1 = PlainType::factory()->create(['name' => 'string']);
        $plainType2 = PlainType::factory()->create(['name' => 'boolean']);

        CustomField::factory()->create([
            'selectable_type' => StringType::class,
            'selectable_id'   => $plainType1->id
        ]);
        CustomField::factory()->create([
            'selectable_type' => BooleanType::class,
            'selectable_id'   => $plainType2->id
        ]);

        CustomField::factory()->create(['selectable_type' => 'NotAPlainType::class']);

        $this->assertEquals(2, CustomField::plain()->get()->count());
    }

    /** @test */
    public function filters_out_remote_types()
    {
        $plainType1 = PlainType::factory()->create(['name' => 'string']);

        RemoteType::factory()->count(2)->create([
            'plain_type_id' => $plainType1->id,
        ]);

        CustomField::factory()->create(['selectable_type' => RemoteType::class, 'selectable_id' => 1]);
        CustomField::factory()->create(['selectable_type' => RemoteType::class, 'selectable_id' => 2]);

        CustomField::factory()->create(['selectable_type' => 'NotARemoteType::class']);

        $this->assertEquals(2, CustomField::remote()->get()->count());
    }

    /** @test */
    public function filters_out_selection_types()
    {
        $plainType1 = PlainType::factory()->create(['name' => 'string']);

        SelectionType::factory()->count(2)->create([
            'plain_type_id' => $plainType1->id,
        ]);

        CustomField::factory()->create(['selectable_type' => SelectionType::class, 'selectable_id' => 1]);
        CustomField::factory()->create(['selectable_type' => SelectionType::class, 'selectable_id' => 2]);

        CustomField::factory()->create(['selectable_type' => 'NotASelectionType::class']);

        $this->assertEquals(2, CustomField::selection()->get()->count());
    }

    /** @test */
    public function will_not_validate_if_validation_not_present()
    {
        $customField1 = CustomField::factory()->create();

        $customField1->validate(['test' => 'test']);

        $this->assertTrue(true);
    }

    /** @test */
    public function has_child_relations()
    {
        CustomField::factory()->count(5)->create();

        /**
         * @var $customField CustomField
         */
        $customField = CustomField::query()->first();

        $customField->children()->attach([2, 3, 4, 5]);

        $this->assertEquals(4, $customField->children->count());
        $this->assertEquals(4, Relation::all()->count());
    }

    /** @test */
    public function has_parent_relations()
    {
        CustomField::factory()->count(5)->create();

        /**
         * @var $customField CustomField
         */
        $customField = CustomField::query()->first();

        $customField->parent()->attach([2, 3, 4, 5]);

        $this->assertEquals(4, $customField->parent->count());
        $this->assertEquals(4, Relation::all()->count());
    }

    /** @test */
    public function flattens_registered_types()
    {
        $types = CustomField::types();

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
        $plainType1 = PlainType::factory()->create(['name' => 'string']);
        $plainType2 = PlainType::factory()->create(['name' => 'integer']);
        $plainType3 = PlainType::factory()->create(['name' => 'float']);
        $plainType4 = PlainType::factory()->create(['name' => 'date']);
        $plainType5 = PlainType::factory()->create(['name' => 'text']);
        $plainType6 = PlainType::factory()->create(['name' => 'boolean']);

        // Plain types

        /**
         * @var $customField1 CustomField
         */
        $customField1 = CustomField::factory()->create([
            'selectable_type' => StringType::class,
            'selectable_id'   => $plainType1->id
        ]);

        $this->assertEquals('string', $customField1->getValueColumn());

        /**
         * @var $customField2 CustomField
         */
        $customField2 = CustomField::factory()->create([
            'selectable_type' => IntegerType::class,
            'selectable_id'   => $plainType2->id
        ]);

        $this->assertEquals('integer', $customField2->getValueColumn());

        /**
         * @var $customField3 CustomField
         */
        $customField3 = CustomField::factory()->create([
            'selectable_type' => FloatType::class,
            'selectable_id'   => $plainType3->id
        ]);

        $this->assertEquals('float', $customField3->getValueColumn());

        /**
         * @var $customField4 CustomField
         */
        $customField4 = CustomField::factory()->create([
            'selectable_type' => DateType::class,
            'selectable_id'   => $plainType4->id
        ]);

        $this->assertEquals('date', $customField4->getValueColumn());

        /**
         * @var $customField5 CustomField
         */
        $customField5 = CustomField::factory()->create([
            'selectable_type' => TextType::class,
            'selectable_id'   => $plainType5->id
        ]);

        $this->assertEquals('text', $customField5->getValueColumn());

        /**
         * @var $customField6 CustomField
         */
        $customField6 = CustomField::factory()->create([
            'selectable_type' => BooleanType::class,
            'selectable_id'   => $plainType6->id
        ]);

        $this->assertEquals('boolean', $customField6->getValueColumn());

        // Remote type
        $remoteType = RemoteType::factory()->create([
            'plain_type_id' => $plainType3->id,
        ]);

        /**
         * @var $customField7 CustomField
         */
        $customField7 = CustomField::factory()->create([
            'selectable_type' => RemoteType::class,
            'selectable_id'   => $remoteType->id
        ]);

        $this->assertEquals('float', $customField7->getValueColumn());

        // Selection type
        $selectionType = SelectionType::factory()->create([
            'plain_type_id' => $plainType5->id,
        ]);

        /**
         * @var $customField8 CustomField
         */
        $customField8 = CustomField::factory()->create([
            'selectable_type' => SelectionType::class,
            'selectable_id'   => $selectionType->id
        ]);

        $this->assertEquals('text', $customField8->getValueColumn());
    }

    /** @test */
    public function falls_back_to_string_value_column_for_unknown_types()
    {
        /**
         * @var $customField CustomField
         */
        $customField = CustomField::factory()->create();

        $this->assertEquals('string', $customField->getValueColumn());
    }

    /** @test */
    public function provides_short_format()
    {
        $plainType = PlainType::factory()->create(['name' => 'string']);

        /**
         * @var $customField CustomField
         */
        $customField = CustomField::factory()->create([
            'name' => 'cf1',
            'selectable_type' => StringType::class,
            'selectable_id' => $plainType->id,
        ]);

        /**
         * @var $value Value
         */
        $value = Value::factory()->create([
            'custom_field_id' => $customField->id,
            'string' => 'some value',
        ]);

        $expected = [
            'cf1' => [
                'type' => 'string',
                'value' => 'some value',
            ],
        ];

        $this->assertEquals($expected, $customField->shortFormat($value->value));
        $this->assertEquals($expected, $value->shortFormat());
    }

    /** @test */
    public function short_format_returns_fallback_for_non_existing_selectable_types()
    {
        /**
         * @var $customField CustomField
         */
        $customField = CustomField::factory()->create(['name' => 'cf1']);

        /**
         * @var $value Value
         */
        $value = Value::factory()->create([
            'custom_field_id' => $customField->id,
            'string' => 'some value',
        ]);

        $expected = [
            'cf1' => [
                'type' => 'string',
                'value' => 'some value',
            ],
        ];

        $this->assertEquals($expected, $customField->shortFormat($value->value));
        $this->assertEquals($expected, $value->shortFormat());
    }
}
