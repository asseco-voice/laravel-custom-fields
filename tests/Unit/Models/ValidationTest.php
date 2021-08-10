<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\Validation;
use Asseco\CustomFields\Database\Factories\ValidationFactory;
use Asseco\CustomFields\Tests\TestCase;
use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ValidationTest extends TestCase
{
    use DatabaseMigrations;

    protected CustomField $customField;
    protected Validation $validation;

    public function setUp(): void
    {
        parent::setUp();

        $this->customField = app(CustomField::class);
        $this->validation = app(Validation::class);
    }

    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(ValidationFactory::class, $this->validation::factory());
    }

    /** @test */
    public function can_fetch_related_custom_fields()
    {
        $validation = $this->validation::factory()->create();

        $customField = $this->customField::factory()->create([
            'validation_id' => $validation->id,
        ]);

        $this->assertEquals($customField->id, $validation->customFields->first()->id);
    }

    /** @test */
    public function validates_regex()
    {
        $validation = $this->validation::factory()->create([
            'regex' => '[A-Z]',
        ]);

        $this->assertNull($validation->validate('ABC'));
    }

    /** @test */
    public function validates_exact_regex()
    {
        $validation = $this->validation::factory()->create([
            'regex' => '123 abc',
        ]);

        $this->assertNull($validation->validate('123 abc'));
    }

    /** @test */
    public function fails_regex_validation()
    {
        $this->expectException(Exception::class);

        $validation = $this->validation::factory()->create([
            'regex' => '[A-Z]',
        ]);

        $validation->validate('abc');
    }
}
