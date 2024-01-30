<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\Contracts\RemoteType;
use Asseco\CustomFields\Database\Factories\RemoteTypeFactory;
use Asseco\CustomFields\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RemoteTypeTest extends TestCase
{
    use DatabaseMigrations;

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
    public function has_factory()
    {
        $this->assertInstanceOf(RemoteTypeFactory::class, $this->remoteType::factory());
    }

    /** @test */
    public function can_fetch_related_custom_fields()
    {
        $remoteType = $this->createRemoteType();

        $customField = $this->customField::factory()->create([
            'selectable_type' => get_class($this->remoteType),
            'selectable_id' => $remoteType->id,
        ]);

        $this->assertEquals($customField->id, $remoteType->customFields->first()->id);
    }

    /** @test */
    public function appends_name_attribute()
    {
        $remoteType = $this->remoteType::factory()->make();

        $this->assertEquals('remote', $remoteType->name);
    }

    protected function createRemoteType()
    {
        return $this->remoteType::factory()->create([
            'plain_type_id' => $this->plainType::factory()->create()->id,
        ]);
    }
}
