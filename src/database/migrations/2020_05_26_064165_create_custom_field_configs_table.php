<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_field_configs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->integer('tenant_id');
            $table->string('name')->unique('cf_name_configs');
            $table->string('model', 255);
            $table->string('label', 255);
            $table->unsignedBigInteger('type');
            $table->foreign('type')->references('id')->on('custom_field_config_types')->onDelete('cascade');

            $table->text('definition');

            $table->unsignedBigInteger('custom_validation_id')->nullable();
            $table->foreign('custom_validation_id')->references('id')->on('custom_field_validations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_field_configs');
    }
}
