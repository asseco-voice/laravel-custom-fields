<?php

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
            $table->id();
            $table->timestamps();

            $table->foreignId('selection_type_id')->constrained('custom_field_selection_types')->onDelete('cascade');

            $table->string('label')->nullable();
            $table->string('value');
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
