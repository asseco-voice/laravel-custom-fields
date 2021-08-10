<?php

use Asseco\BlueprintAudit\App\MigrationMethodPicker;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldPlainTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_field_plain_types', function (Blueprint $table) {

            if (config('asseco-custom-fields.migrations.uuid')) {
                $table->uuid('id')->primary();
            } else {
                $table->id();
            }

            $table->string('name', 150)->unique('cf_name_types');

            MigrationMethodPicker::pick($table, config('asseco-custom-fields.migrations.timestamps'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_field_plain_types');
    }
}
