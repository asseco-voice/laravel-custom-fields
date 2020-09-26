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

//            $table->string('tenant_id', 30)->nullable();
            $table->string('name')->unique('cf_name');
            $table->string('label', 255);
            $table->string('placeholder')->nullable();
            $table->morphs('selectable');
            $table->string('model');
            $table->boolean('required')->default(0);
            $table->foreignId('validation_id')->nullable()->constrained('custom_field_validations')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
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
