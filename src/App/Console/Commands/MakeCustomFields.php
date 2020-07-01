<?php

namespace Voice\CustomFields\App\Console\Commands;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Database\Console\Migrations\TableGuesser;
use Illuminate\Support\Str;
use InvalidArgumentException;

class MakeCustomFields extends MigrateMakeCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'asseco-voice:custom-fields
        {--path= : The location where the migration files should be created}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
        {--fullpath : Output the full path of the migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating custom fields migrations for models having Customizable trait';

    public function handle()
    {
        $models = $this->getModelsWithCustomizableTrait();

        foreach ($models as $model) {
            $this->createMigration($model);
        }

        $this->composer->dumpAutoloads();
    }

    protected function getModelsWithCustomizableTrait()
    {
        $path = config('asseco-voice.custom_fields.models_path');
        $namespace = config('asseco-voice.custom_fields.model_namespace');
        $models = [];
        $results = scandir($path);

        foreach ($results as $result) {
            if ($result === '.' or $result === '..') continue;

            $filename = $path . '/' . $result;

            if (is_dir($filename)) continue;

            $result = substr($result, 0, -4);

            if ($this->hasCustomizableTrait($namespace . $result)) {
                $models[] = $result;
            }
        }
        return $models;
    }

    protected function hasCustomizableTrait($class)
    {
        $traits = class_uses($class);
        $customizable = config('asseco-voice.custom_fields.trait_path');

        return in_array($customizable, $traits);
    }

    protected function createMigration($model)
    {
        $modelSnakeCase = Str::snake(class_basename($model));

        $models = ['custom_field', $modelSnakeCase];
        sort($models);

        $name = "create_{$models[0]}_{$models[1]}_table";

        [$table, $create] = TableGuesser::guess($name);

        try {
            $this->writeMigrationOverloaded($name, $table, $create, $model);
        } catch (InvalidArgumentException $e) {
            $this->line("Migration {$name} already exists. Skipping...");
            return;
        }
    }

    protected function writeMigrationOverloaded($name, $table, $create, $model)
    {
        $file = $this->creator->createOverloaded(
            $name, $this->getMigrationPath(), $model, $table, $create
        );

        if (!$this->option('fullpath')) {
            $file = pathinfo($file, PATHINFO_FILENAME);
        }

        $this->line("<info>Created Migration:</info> {$file}");
    }

}
