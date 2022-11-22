<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_field_remote_types', function (Blueprint $table) {
            $table->string('data_path')->nullable()->after('mappings');
            $table->string('identifier_property')->nullable()->after('mappings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_field_remote_types', function (Blueprint $table) {
            $table->dropColumn('data_path');
        });

        Schema::table('custom_field_remote_types', function (Blueprint $table) {
            $table->dropColumn('identifier_property');
        });
    }
};
