<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Feature;

use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\PlainType;
use Asseco\CustomFields\App\PlainTypes\BooleanType;
use Asseco\CustomFields\App\PlainTypes\StringType;
use Asseco\CustomFields\Tests\TestCase;

class PlainCustomFieldControllerTest extends TestCase
{
    /** @test */
    public function returns_only_plain_custom_fields()
    {
        $this
            ->getJson(route('custom-field.plain.index'))
            ->assertJsonCount(0);

        $plainType1 = PlainType::factory()->create(['name' => 'string']);
        $plainType2 = PlainType::factory()->create(['name' => 'boolean']);

        CustomField::factory()->create([
            'selectable_type' => StringType::class,
            'selectable_id'   => $plainType1->id,
        ]);
        CustomField::factory()->create([
            'selectable_type' => BooleanType::class,
            'selectable_id'   => $plainType2->id,
        ]);

        CustomField::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.plain.index'))
            ->assertJsonCount(2);

        $this->assertCount(7, CustomField::all());
    }

    /** @test */
    public function rejects_creating_plain_custom_field_with_invalid_name()
    {
        $request = CustomField::factory()->make([
            'name' => 'invalid name',
        ])->toArray();

        $this
            ->postJson(route('custom-field.plain.store', 'string'), $request)
            ->assertStatus(422);
    }

    /** @test */
    public function creates_plain_custom_field()
    {
        PlainType::factory()->create(['name' => 'string']);

        $request = CustomField::factory()->make()->toArray();

        $this
            ->postJson(route('custom-field.plain.store', 'string'), $request)
            ->assertJsonFragment([
                'id'   => 1,
                'name' => $request['name'],
            ]);

        $this->assertCount(1, CustomField::all());
    }
}
