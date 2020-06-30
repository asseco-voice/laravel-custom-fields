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

            $table->string('record_id', 36)->index('record_id');

            $table->unsignedBigInteger('custom_field_config_id');
            $table->foreign('custom_field_config_id')->references('id')->on('custom_field_configs')->onDelete('cascade');

            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('custom_fields')->onDelete('cascade');

            $table->string('value_string', 255)->nullable();
            $table->decimal('value_number', 18,6)->nullable();
            $table->timestamp('value_date')->nullable();
            $table->text('value_text')->nullable();

            $table->index(['record_id', 'custom_field_config_id']);
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
