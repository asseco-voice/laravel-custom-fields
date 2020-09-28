<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldValidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_field_validations', function (Blueprint $table) {
            $table->id();

            $table->string('name', 150)->unique('cf_name_validations');
            $table->string('regex', 255)->nullable();

            // Set to true for predefined validations which should be shown on frontend dropdown.
            $table->boolean('generic')->default(false);

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
        Schema::dropIfExists('custom_field_validations');
    }
}
