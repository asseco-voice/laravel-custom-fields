<?php

use Asseco\BlueprintAudit\App\MigrationMethodPicker;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldRemoteTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_field_remote_types', function (Blueprint $table) {
            if (config('asseco-custom-fields.migrations.uuid')) {
                $table->uuid('id')->primary();
                $table->foreignUuid('plain_type_id')->constrained('custom_field_plain_types');
            } else {
                $table->id();
                $table->foreignId('plain_type_id')->constrained('custom_field_plain_types');
            }

            $table->string('url');
            $table->enum('method', ['GET', 'POST', 'PUT']);
            $table->json('body')->nullable();
            $table->json('headers')->nullable();
            $table->json('mappings')->nullable();

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
        Schema::dropIfExists('custom_field_remote_types');
    }
}
