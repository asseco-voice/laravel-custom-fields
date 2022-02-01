<?php

use Asseco\BlueprintAudit\App\MigrationMethodPicker;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_templates', function (Blueprint $table) {
            if (config('asseco-custom-fields.migrations.uuid')) {
                $table->uuid('id')->primary();
                $table->foreignUuid('form_id')->constrained();
            } else {
                $table->id();
                $table->foreignId('form_id')->constrained();
            }

            $table->string('name');
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
        Schema::table('form_templates', function (Blueprint $table) {
            $table->dropIfExists('form_templates');
        });
    }
}
