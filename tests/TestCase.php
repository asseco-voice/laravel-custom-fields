<?php

namespace Asseco\CustomFields\Tests;

use Asseco\CustomFields\CustomFieldsServiceProvider;
use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\SQLiteBuilder;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Fluent;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        $this->hotfixSqlite();

        parent::setUp();

        $this->runLaravelMigrations();
    }

    /**
     *
     */
    public function hotfixSqlite()
    {
        \Illuminate\Database\Connection::resolverFor('sqlite',
            function ($connection, $database, $prefix, $config) {
                return new class($connection, $database, $prefix, $config)
                    extends SQLiteConnection {
                    public function getSchemaBuilder()
                    {
                        if ($this->schemaGrammar === null) {
                            $this->useDefaultSchemaGrammar();
                        }

                        return new class($this) extends SQLiteBuilder {
                            protected function createBlueprint($table, Closure $callback = null)
                            {
                                return new class($table, $callback) extends Blueprint {
                                    public function dropForeign($index)
                                    {
                                        return new Fluent();
                                    }
                                };
                            }
                        };
                    }
                };
            });
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
