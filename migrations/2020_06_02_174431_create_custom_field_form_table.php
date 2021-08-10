<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldFormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_field_form', function (Blueprint $table) {
            $table->id();

            if (config('asseco-custom-fields.migrations.uuid')) {
                $table->foreignUuid('custom_field_id')->constrained()->cascadeOnDelete();
                $table->foreignUuid('form_id')->constrained()->cascadeOnDelete();
            } else {
                $table->foreignId('custom_field_id')->constrained()->cascadeOnDelete();
                $table->foreignId('form_id')->constrained()->cascadeOnDelete();
            }

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_field_form');
    }
}
