<?php

use Asseco\BlueprintAudit\App\MigrationMethodPicker;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            if (config('asseco-custom-fields.migrations.uuid')) {
                $table->uuid('id')->primary();
                $table->uuidMorphs('selectable');
                $table->foreignUuid('validation_id')->nullable()->constrained('custom_field_validations')->nullOnDelete();
            } else {
                $table->id();
                $table->morphs('selectable');
                $table->foreignId('validation_id')->nullable()->constrained('custom_field_validations')->nullOnDelete();
            }

            $table->string('name')->unique('cf_name');
            $table->string('label', 255);
            $table->string('placeholder')->nullable();
            $table->string('model');
            $table->boolean('required')->default(0);
            $table->string('group')->nullable();
            $table->integer('order')->nullable();

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
        Schema::dropIfExists('custom_fields');
    }
}
