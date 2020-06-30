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
    protected $description = 'Creating customfields migrations for models having Containable trait';
}
