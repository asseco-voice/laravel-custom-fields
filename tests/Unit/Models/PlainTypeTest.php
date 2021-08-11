<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Contracts\Mappable;
use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\Database\Factories\PlainTypeFactory;
use Asseco\CustomFields\Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use TypeError;

class PlainTypeTest extends TestCase
{
    use DatabaseMigrations;

    protected PlainType $plainType;

    public function setUp(): void
    {
        parent::setUp();

        $this->plainType = app(PlainType::class);
    }

    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(PlainTypeFactory::class, $this->plainType::factory());
    }

    /** @test */
    public function has_basic_sub_types()
    {
        $plainMappings = $this->plainType::subTypes();

        $this->assertNotNull($plainMappings);

        $this->assertArrayHasKey('string', $plainMappings);
        $this->assertArrayHasKey('integer', $plainMappings);
        $this->assertArrayHasKey('float', $plainMappings);
        $this->assertArrayHasKey('date', $plainMappings);
        $this->assertArrayHasKey('text', $plainMappings);
        $this->assertArrayHasKey('boolean', $plainMappings);

        return $plainMappings;
    }

    /**
     * @test
     * @depends has_basic_sub_types
     * @param array $plainMappings
     */
    public function sub_types_have_registered_classes(array $plainMappings)
    {
        foreach ($plainMappings as $typeName => $typeClass) {
            $class = $this->plainType::getSubTypeClass($typeName);

            $this->assertEquals($typeClass, $class);
        }
    }

    /** @test */
    public function throws_error_on_non_existing_class()
    {
        $this->expectException(TypeError::class);

        $this->plainType::getSubTypeClass(now()->timestamp);
    }

    /**
     * @test
     * @depends has_basic_sub_types
     * @param $basicSubTypes
     */
    public function returns_pipe_delimited_sub_types(array $basicSubTypes)
    {
        $regexSubTypes = $this->plainType::getRegexSubTypes();

        $this->assertEquals(implode('|', array_keys($basicSubTypes)), $regexSubTypes);
    }

    /**
     * @test
     * @depends has_basic_sub_types
     * @param array $basicSubTypes
     */
    public function sub_types_are_scoped_correctly(array $basicSubTypes)
    {
        $this->plainType::factory()->count(5)->create();

        foreach ($basicSubTypes as $typeName => $typeClass) {
            $plainType = $this->plainType::factory()->create(['name' => $typeName]);
            $instance = new $typeClass;

            $this->assertInstanceOf(Mappable::class, $instance);
            $this->assertInstanceOf(get_class($this->plainType), $instance);

            /**
             * @var $instance Model
             */
            $subType = $instance::query()->first();

            $this->assertEquals($plainType->id, $subType->id);
        }
    }
}
