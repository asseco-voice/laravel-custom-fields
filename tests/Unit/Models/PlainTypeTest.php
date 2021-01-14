<?php

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Contracts\Mappable;
use Asseco\CustomFields\App\Models\PlainType;
use Asseco\CustomFields\App\PlainTypes\DateType;
use Asseco\CustomFields\App\PlainTypes\FloatType;
use Asseco\CustomFields\App\PlainTypes\IntegerType;
use Asseco\CustomFields\App\PlainTypes\StringType;
use Asseco\CustomFields\App\PlainTypes\TextType;
use Asseco\CustomFields\Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use TypeError;

class PlainTypeTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function has_basic_sub_types()
    {
        $plainMappings = PlainType::subTypes();

        $this->assertNotNull($plainMappings);

        $this->assertArrayHasKey('string', $plainMappings);
        $this->assertArrayHasKey('integer', $plainMappings);
        $this->assertArrayHasKey('float', $plainMappings);
        $this->assertArrayHasKey('date', $plainMappings);
        $this->assertArrayHasKey('text', $plainMappings);
        $this->assertArrayHasKey('boolean', $plainMappings);

        return $plainMappings;
    }

    /** @test */
    public function returns_string_class()
    {
        $class = PlainType::getSubTypeClass('string');

        $this->assertEquals(StringType::class, $class);
    }

    /** @test */
    public function returns_integer_class()
    {
        $class = PlainType::getSubTypeClass('integer');

        $this->assertEquals(IntegerType::class, $class);
    }

    /** @test */
    public function returns_float_class()
    {
        $class = PlainType::getSubTypeClass('float');

        $this->assertEquals(FloatType::class, $class);
    }

    /** @test */
    public function returns_date_class()
    {
        $class = PlainType::getSubTypeClass('date');

        $this->assertEquals(DateType::class, $class);
    }

    /** @test */
    public function returns_text_class()
    {
        $class = PlainType::getSubTypeClass('text');

        $this->assertEquals(TextType::class, $class);
    }

    /** @test */
    public function throws_error_on_non_existing_class()
    {
        $this->expectException(TypeError::class);

        PlainType::getSubTypeClass(now()->timestamp);
    }

    /**
     * @test
     * @depends has_basic_sub_types
     * @param $basicSubTypes
     */
    public function returns_regex_format_for_sub_types(array $basicSubTypes)
    {
        $regexSubTypes = PlainType::getRegexSubTypes();

        $this->assertEquals(implode('|', array_keys($basicSubTypes)), $regexSubTypes);
    }

    /**
     * @test
     * @depends has_basic_sub_types
     * @param array $basicSubTypes
     */
    public function sub_types_are_scoped_correctly(array $basicSubTypes)
    {
        PlainType::factory()->count(5)->create();

        foreach ($basicSubTypes as $typeName => $typeClass) {

            $plainType = PlainType::factory()->create(['name' => $typeName]);
            $instance = new $typeClass;

            $this->assertInstanceOf(Mappable::class, $instance);
            $this->assertInstanceOf(PlainType::class, $instance);

            /**
             * @var $instance Model
             */
            $subType = $instance::query()->first();

            $this->assertEquals($plainType->id, $subType->id);
        }
    }
}
