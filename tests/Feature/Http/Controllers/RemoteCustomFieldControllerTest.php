<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Feature\Http\Controllers;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\Contracts\RemoteType;
use Asseco\CustomFields\Tests\TestCase;

class RemoteCustomFieldControllerTest extends TestCase
{
    protected CustomField $customField;
    protected PlainType $plainType;
    protected RemoteType $remoteType;

    public function setUp(): void
    {
        parent::setUp();

        $this->customField = app(CustomField::class);
        $this->plainType = app(PlainType::class);
        $this->remoteType = app(RemoteType::class);
    }

    /** @test */
    public function returns_only_remote_custom_fields()
    {
        $this
            ->getJson(route('custom-field.remote.index'))
            ->assertJsonCount(0);

        $remoteType = $this->remoteType::factory()->create([
            'plain_type_id' => $this->plainType::query()->where('name', 'string')->first()->id,
        ]);

        $this->customField::factory()->create([
            'selectable_type' => get_class($this->remoteType),
            'selectable_id' => $remoteType->id,
        ]);

        $this->customField::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.remote.index'))
            ->assertJsonCount(1);

        $this->assertCount(6, $this->customField::all());
    }

    /** @test */
    public function rejects_creating_remote_custom_field_with_invalid_name()
    {
        $request = $this->customField::factory()->make([
            'name' => 'invalid name',
        ])->toArray();

        $this
            ->postJson(route('custom-field.remote.store'), $request)
            ->assertStatus(422);
    }

    /** @test */
    public function rejects_creating_remote_custom_field_without_remote_parameters()
    {
        $request = $this->customField::factory()->make()->toArray();

        $this
            ->postJson(route('custom-field.remote.store'), $request)
            ->assertStatus(422);
    }

    /** @test */
    public function creates_remote_custom_field()
    {
        $request = $this->customField::factory()->make()->toArray();

        $request['remote'] = $this->remoteType::factory()->make([
            'plain_type_id' => $this->plainType::query()->where('name', 'string')->first()->id,
            'body' => [],
            'mappings' => [],
        ])->toArray();

        $this
            ->postJson(route('custom-field.remote.store'), $request)
            ->assertJsonFragment([
                'name' => $request['name'],
            ]);

        $this->assertCount(1, $this->customField::all());
    }
}
