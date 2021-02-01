<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\Form;
use Asseco\CustomFields\App\Models\Validation;
use Asseco\CustomFields\App\PlainTypes\StringType;
use Asseco\CustomFields\Database\Factories\FormFactory;
use Asseco\CustomFields\Tests\TestCase;
use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FormTest extends TestCase
{
    use DatabaseMigrations;

    protected CustomField $cf1;
    protected CustomField $cf2;

    public function setUp(): void
    {
        parent::setUp();

        $validation = Validation::factory()->create(['regex' => '[A-Z]']);

        $this->cf1 = CustomField::factory()->create([
            'name'          => 'cf1',
            'validation_id' => $validation->id,
            'required'      => 0,
        ]);

        $this->cf2 = CustomField::factory()->create([
            'name'     => 'cf2',
            'required' => 1,
        ]);
    }

    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(FormFactory::class, Form::factory());
    }

    /** @test */
    public function relates_custom_fields_from_components()
    {
        $form = Form::factory()->create([
            'definition' => [
                'components' => [
                    ['key' => 'cf1'],
                    ['key' => 'cf2'],
                ],
            ],
        ]);

        $this->assertCount(2, $form->customFields);
    }

    /** @test */
    public function relates_custom_fields_from_inner_components()
    {
        $form = Form::factory()->create([
            'definition' => [
                'components' => [
                    'components' => [
                        ['key' => 'cf1'],
                        ['key' => 'cf2'],
                    ],
                ],
            ],
        ]);

        $this->assertCount(2, $form->customFields);
    }

    /** @test */
    public function relates_custom_fields_from_mixed_components()
    {
        $form = Form::factory()->create([
            'definition' => [
                'components' => [
                    ['key' => 'cf1'],
                    'components' => [
                        ['key' => 'cf2'],
                    ],
                ],
            ],
        ]);

        $this->assertCount(2, $form->customFields);
    }

    /** @test */
    public function relates_only_existing_custom_fields()
    {
        $form = Form::factory()->create([
            'definition' => [
                'components' => [
                    ['key' => 'cf1'],
                    ['key' => 'cf123'],
                ],
            ],
        ]);

        $this->assertCount(1, $form->customFields);
    }

    /** @test */
    public function relates_nothing()
    {
        $form = Form::factory()->create();

        $this->assertCount(0, $form->customFields);
    }

    /** @test */
    public function ignores_defined_form_components()
    {
        $form = Form::factory()->create([
            'definition' => [
                'components' => [
                    ['key' => 'htmlelement'],
                    ['key' => 'content'],
                    ['key' => 'columns'],
                ],
            ],
        ]);

        $this->assertCount(0, $form->customFields);
    }

    /** @test */
    public function updates_custom_fields_if_form_definition_changes()
    {
        $form = Form::factory()->create([
            'definition' => [
                'components' => [
                    ['key' => 'cf1'],
                ],
            ],
        ]);

        $this->assertCount(1, $form->customFields);
        $this->assertEquals('cf1', $form->customFields->first()->name);

        $form->update([
            'definition' => [
                'components' => [
                    ['key' => 'cf2'],
                ],
            ],
        ]);

        $this->assertCount(1, $form->customFields);
        $this->assertEquals('cf2', $form->customFields->first()->name);

        $form->update([
            'definition' => [
                'components' => [
                ],
            ],
        ]);

        $this->assertCount(0, $form->customFields);

        $form->update([
            'definition' => [
                'components' => [
                    ['key' => 'cf1'],
                    ['key' => 'cf2'],
                ],
            ],
        ]);

        $this->assertCount(2, $form->customFields);
    }

    /** @test */
    public function validates_empty_input()
    {
        /**
         * @var $form Form
         */
        $form = Form::factory()->create();

        try {
            $form->validate([]);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        $this->assertTrue(true);
    }

    /** @test */
    public function validates_valid_input()
    {
        /**
         * @var $form Form
         */
        $form = Form::factory()->create([
            'definition' => [
                'components' => [
                    ['key' => 'cf1'],
                ],
            ],
        ]);

        try {
            $form->validate(['cf1' => 'ABC']);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        $this->assertTrue(true);
    }

    /** @test */
    public function validates_invalid_input()
    {
        $this->expectException(Exception::class);

        /**
         * @var $form Form
         */
        $form = Form::factory()->create([
            'definition' => [
                'components' => [
                    ['key' => 'cf1'],
                ],
            ],
        ]);

        $form->validate(['cf1' => 'abc']);
    }

    /** @test */
    public function should_throw_on_missing_required_fields()
    {
        $this->expectException(Exception::class);

        /**
         * @var $form Form
         */
        $form = Form::factory()->create([
            'definition' => [
                'components' => [
                    ['key' => 'cf2'],
                ],
            ],
        ]);

        $form->validate([]);
    }

    /** @test */
    public function creates_values_from_given_form_data()
    {
        $this->cf1->update([
            'selectable_type' => StringType::class,
        ]);

        /**
         * @var $form Form
         */
        $form = Form::factory()->create([
            'definition' => [
                'components' => [
                    ['key' => 'cf1'],
                ],
            ],
        ]);

        $form->createValues(['cf1' => 'test'], 'Test::class', 1);

        $value = $form->customFields->first()->values->first();

        $this->assertEquals('test', $value->string);
        $this->assertNull($value->integer);
        $this->assertNull($value->text);
        $this->assertNull($value->date);
        $this->assertNull($value->float);
    }

    /** @test */
    public function does_not_create_anything_on_invalid_form_data()
    {
        /**
         * @var $form Form
         */
        $form = Form::factory()->create([
            'definition' => [
                'components' => [
                    ['key' => 'cf1'],
                ],
            ],
        ]);

        $form->createValues(['noExistingCf' => 'test'], 'Test::class', 1);

        $this->assertNull($form->customFields->first()->values->first());
    }

    /** @test */
    public function updates_custom_field_values()
    {
        $this->cf1->update([
            'selectable_type' => StringType::class,
        ]);

        /**
         * @var $form Form
         */
        $form = Form::factory()->create([
            'definition' => [
                'components' => [
                    ['key' => 'cf1'],
                ],
            ],
        ]);

        $form->createValues(['cf1' => 'test'], 'Test::class', 1);

        $this->assertCount(1, $form->customFields->first()->values);

        $form->createValues(['cf1' => 'test updated'], 'Test::class', 1);
        $form->refresh();

        $this->assertCount(1, $form->customFields->first()->values);
        $this->assertEquals('test updated', $form->customFields->first()->values->first()->value);
    }

    /** @test */
    public function accepts_valid_form_names()
    {
        Form::factory()->create(['name' => 'valid_name']);

        $this->assertTrue(true);
    }

    /** @test */
    public function rejects_form_name_containing_spaces()
    {
        $this->expectException(Exception::class);

        Form::factory()->create(['name' => 'not a valid name']);
    }

    /** @test */
    public function returns_short_format_for_validated_fields()
    {
        /**
         * @var $form Form
         */
        $form = Form::factory()->create([
            'definition' => [
                'components' => [
                    ['key' => 'cf1'],
                    ['key' => 'cf2'],
                ],
            ],
        ]);

        $expected = [
            'cf1' => [
                'type'  => 'string',
                'value' => 'ABC'
            ],
            'cf2' => [
                'type'  => 'string',
                'value' => 'ABC'
            ]
        ];

        $this->assertEquals($expected, $form->validate(['cf1' => 'ABC', 'cf2' => 'ABC',]));
    }
}
