<?php

use Asseco\BlueprintAudit\App\MigrationMethodPicker;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldSelectionValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_field_selection_values', function (Blueprint $table) {
            if (config('asseco-custom-fields.migrations.uuid')) {
                $table->uuid('id')->primary();
                $table->foreignUuid('selection_type_id')->constrained('custom_field_selection_types')->cascadeOnDelete();
            } else {
                $table->id();
                $table->foreignId('selection_type_id')->constrained('custom_field_selection_types')->cascadeOnDelete();
            }

            $table->string('label')->nullable();
            $table->string('value');
            $table->boolean('preselect')->default(false);

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
        Schema::dropIfExists('custom_field_selection_values');
    }
}
