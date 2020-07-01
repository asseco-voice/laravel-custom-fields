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
            // TODO: vidjeti kakav će uopće biti model i baciti u novu tablicu ako treba
            $table->string('model', 255);
            $table->string('label', 255);
            $table->text('definition');

            $table->foreignId('custom_field_config_type_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('custom_field_configs');
    }
}
