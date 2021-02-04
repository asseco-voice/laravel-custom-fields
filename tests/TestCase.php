<?php

namespace Asseco\CustomFields\Tests;

use Asseco\CustomFields\CustomFieldsServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->runLaravelMigrations();
    }

    protected function getPackageProviders($app)
    {
        return [CustomFieldsServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
