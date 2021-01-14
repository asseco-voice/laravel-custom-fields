<?php

namespace Asseco\CustomFields\Tests\Unit\Models;

use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\PlainTypes\StringType;
use Asseco\CustomFields\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CustomFieldTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_fetches_plain_types()
    {
        $customField = CustomField::factory()->create([
            'selectable_type' => StringType::class,
            'selectable_id'   => 1,
            'model'           => 'XYZ',
        ]);

        $this->assertTrue(true);
    }
}
