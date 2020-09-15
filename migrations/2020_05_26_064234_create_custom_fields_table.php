<?php

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
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->string('tenant_id', 30)->nullable();

            $table->string('model');
            $table->string('name')->unique('cf_name_configs');
            $table->string('label', 255);
            $table->json('definition');
            $table->boolean('required')->default(0);

            $table->foreignId('custom_field_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('custom_field_validation_id')->constrained()->onDelete('cascade');
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
