<?php

namespace Voice\CustomFields;

use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\Str;

class CustomMigrationCreator extends MigrationCreator
{
    /**
     * Create a new migration at the given path.
     *
     * @param string $name
     * @param string $path
     * @param $model
     * @param string|null $table
     * @param bool $create
     * @return string
     *
     */
    public function createOverloaded($name, $path, $model, $table = null, $create = false)
    {
        $this->ensureMigrationDoesntAlreadyExist($name, $path);

        // First we will get the stub file for the migration, which serves as a type
        // of template for the migration. Once we have those we will populate the
        // various place-holders, save the file, and run the post create event.
        $stub = $this->getStub($table, $create);

        $this->files->put(
            $path = $this->getPath($name, $path),
            $this->populateStubOverloaded($name, $stub, $table, $model)
        );

        // Next, we will fire any hooks that are supposed to fire after a migration is
        // created. Once that is done we'll be ready to return the full path to the
        // migration file so it can be used however it's needed by the developer.
        $this->firePostCreateHooks($table);

        return $path;
    }

    /**
     * Populate the place-holders in the migration stub.
     *
     * @param string $name
     * @param string $stub
     * @param string|null $table
     * @param $model
     * @return string
     */
    protected function populateStubOverloaded($name, $stub, $table, $model)
    {
        $snakeCaseModel = Str::snake(class_basename($model));
        $foreignId = "{$snakeCaseModel}_id";

        $stub = str_replace('{{ foreignId }}', $foreignId, $stub);

        $stub = str_replace(
            ['DummyClass', '{{ class }}', '{{class}}'],
            $this->getClassName($name), $stub
        );

        if (!is_null($table)) {
            $stub = str_replace(
                ['DummyTable', '{{ table }}', '{{table}}'],
                $table, $stub
            );
        }

        return $stub;
    }
}
