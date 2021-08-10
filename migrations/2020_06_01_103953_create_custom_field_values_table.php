<?php

use Asseco\BlueprintAudit\App\MigrationMethodPicker;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_field_values', function (Blueprint $table) {
            if (config('asseco-custom-fields.migrations.uuid')) {
                $table->uuid('id')->primary();
                $table->foreignUuid('custom_field_id')->constrained()->cascadeOnDelete();
                $table->uuidMorphs('model');
            } else {
                $table->id();
                $table->foreignId('custom_field_id')->constrained()->cascadeOnDelete();
                $table->morphs('model');
            }

            $table->string('string', 255)->nullable();
            $table->integer('integer')->nullable();
            $table->float('float', 18, 6)->nullable();
            $table->text('text')->nullable();
            $table->boolean('boolean')->nullable();
            $table->datetime('datetime')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();

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
        Schema::dropIfExists('custom_field_values');
    }
}
