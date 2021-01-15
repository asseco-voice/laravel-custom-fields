<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\PlainType;
use Asseco\CustomFields\App\Models\RemoteType;
use Asseco\CustomFields\Database\Factories\RemoteTypeFactory;
use Asseco\CustomFields\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RemoteTypeTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(RemoteTypeFactory::class, RemoteType::factory());
    }

    /** @test */
    public function can_fetch_related_custom_fields()
    {
        $remoteType = $this->createRemoteType();

        $customField = CustomField::factory()->create([
            'selectable_type' => RemoteType::class,
            'selectable_id'   => $remoteType->id,
        ]);

        $this->assertEquals($customField->id, $remoteType->customFields->first()->id);
    }

    /** @test */
    public function appends_name_attribute()
    {
        $remoteType = RemoteType::factory()->make();

        $this->assertEquals('remote', $remoteType->name);
    }

    protected function createRemoteType()
    {
        return RemoteType::factory()->create([
            'plain_type_id' => PlainType::factory()->create()->id,
        ]);
    }
}
