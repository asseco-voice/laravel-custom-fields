<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Feature\Http\Controllers;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\PlainTypes\BooleanType;
use Asseco\CustomFields\App\PlainTypes\StringType;
use Asseco\CustomFields\Tests\TestCase;

class PlainCustomFieldControllerTest extends TestCase
{
    protected CustomField $customField;
    protected PlainType $plainType;

    public function setUp(): void
    {
        parent::setUp();

        $this->customField = app(CustomField::class);
        $this->plainType = app(PlainType::class);
    }

    /** @test */
    public function returns_only_plain_custom_fields()
    {
        $plainType1 = $this->plainType::query()->where('name', 'string')->first();
        $plainType2 = $this->plainType::query()->where('name', 'boolean')->first();

        $this->customField::factory()->create([
            'selectable_type' => StringType::class,
            'selectable_id' => $plainType1->id,
        ]);
        $this->customField::factory()->create([
            'selectable_type' => BooleanType::class,
            'selectable_id' => $plainType2->id,
        ]);

        $this->customField::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.plain.index'))
            ->assertJsonCount(2);

        $this->assertCount(7, $this->customField::all());
    }

    /** @test */
    public function rejects_creating_plain_custom_field_with_invalid_name()
    {
        $request = $this->customField::factory()->make([
            'name' => 'invalid name',
        ])->toArray();

        $this
            ->postJson(route('custom-field.plain.store', 'string'), $request)
            ->assertStatus(422);
    }

    /** @test */
    public function creates_plain_custom_field()
    {
        $request = $this->customField::factory()->make()->toArray();

        $this
            ->postJson(route('custom-field.plain.store', 'string'), $request)
            ->assertJsonFragment([
                'id' => 1,
                'name' => $request['name'],
            ]);

        $this->assertCount(1, $this->customField::all());
    }
}
