<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Models\Form;
use Asseco\CustomFields\Database\Factories\FormFactory;
use Asseco\CustomFields\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FormTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function has_factory()
    {
        $this->assertInstanceOf(FormFactory::class, Form::factory());
    }

}
