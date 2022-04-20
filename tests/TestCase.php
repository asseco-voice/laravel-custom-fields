<?php

namespace Asseco\CustomFields\Tests;

use Asseco\Common\CommonServiceProvider;
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
        return [CustomFieldsServiceProvider::class, CommonServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
