<?php

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\Validation;
use Asseco\CustomFields\Database\Factories\ValidationFactory;
use Asseco\CustomFields\Tests\TestCase;
use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ValidationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_fetch_related_custom_fields()
    {
        $validation = Validation::factory()->create();

        $customField = CustomField::factory()->create([
            'validation_id' => $validation->id,
        ]);

        $this->assertEquals($customField->id, $validation->customFields->first()->id);
    }

    /** @test */
    public function validates_regex()
    {
        $validation = Validation::factory()->create([
            'regex' => '[A-Z]',
        ]);

        $this->assertNull($validation->validate('ABC'));
    }

    /** @test */
    public function validates_exact_regex()
    {
        $validation = Validation::factory()->create([
            'regex' => '123 abc',
        ]);

        $this->assertNull($validation->validate('123 abc'));
    }

    /** @test */
    public function fails_regex_validation()
    {
        $this->expectException(Exception::class);

        $validation = Validation::factory()->create([
            'regex' => '[A-Z]',
        ]);

        $validation->validate('abc');
    }

    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(ValidationFactory::class, Validation::factory());
    }
}
