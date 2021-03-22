<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Feature\Http\Controllers;

use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\PlainType;
use Asseco\CustomFields\App\Models\RemoteType;
use Asseco\CustomFields\Tests\TestCase;

class RemoteCustomFieldControllerTest extends TestCase
{
    /** @test */
    public function returns_only_remote_custom_fields()
    {
        $this
            ->getJson(route('custom-field.remote.index'))
            ->assertJsonCount(0);

        $remoteType = RemoteType::factory()->create([
            'plain_type_id' => PlainType::factory()->create(['name' => 'string'])->id,
        ]);

        CustomField::factory()->create([
            'selectable_type' => RemoteType::class,
            'selectable_id'   => $remoteType->id,
        ]);

        CustomField::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.remote.index'))
            ->assertJsonCount(1);

        $this->assertCount(6, CustomField::all());
    }

    /** @test */
    public function rejects_creating_remote_custom_field_with_invalid_name()
    {
        $request = CustomField::factory()->make([
            'name' => 'invalid name',
        ])->toArray();

        $this
            ->postJson(route('custom-field.remote.store'), $request)
            ->assertStatus(422);
    }

    /** @test */
    public function rejects_creating_remote_custom_field_without_remote_parameters()
    {
        $request = CustomField::factory()->make()->toArray();

        $this
            ->postJson(route('custom-field.remote.store'), $request)
            ->assertStatus(422);
    }

    /** @test */
    public function creates_remote_custom_field()
    {
        $request = CustomField::factory()->make()->toArray();

        $request['remote'] = RemoteType::factory()->make([
            'plain_type_id' => PlainType::factory()->create(['name' => 'string'])->id,
            'body'          => [],
            'mappings'      => [],
        ])->toArray();

        $this
            ->postJson(route('custom-field.remote.store'), $request)
            ->assertJsonFragment([
                'name' => $request['name'],
            ]);

        $this->assertCount(1, CustomField::all());
    }
}
