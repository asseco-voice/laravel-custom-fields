<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_field_relations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('custom_field_parent')->constrained('custom_fields')->onDelete('cascade');
            $table->foreignId('custom_field_child')->constrained('custom_fields')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_field_relations');
    }
}
