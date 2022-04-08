<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUniqueIndexToCustomFieldSelectionValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->consolidate();

        Schema::table('custom_field_selection_values', function (Blueprint $table) {
            $table->unique(['selection_type_id', 'value']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_field_selection_values', function (Blueprint $table) {
            $table->dropForeign(['selection_type_id']);
        });

        Schema::table('custom_field_selection_values', function (Blueprint $table) {
            $table->dropUnique(['selection_type_id', 'value']);
        });

        Schema::table('custom_field_selection_values', function (Blueprint $table) {
            $table
                ->foreign('selection_type_id')
                ->references('id')
                ->on('custom_field_selection_types')
                ->cascadeOnDelete();
        });
    }

    protected function consolidate()
    {
        $selectionValues = DB::table('custom_field_selection_values')->get();

        foreach ($selectionValues as $selectionValue) {

            if (DB::table('custom_field_selection_values')->where('id', $selectionValue->id)->exists()) {
                $ids = DB::table('custom_field_selection_values')
                    ->where('selection_type_id', $selectionValue->selection_type_id)
                    ->where('value', $selectionValue->value)
                    ->where('id', '!=', $selectionValue->id)
                    ->pluck('id');

                DB::table('custom_field_selection_values')->whereIn('id', $ids)->delete();
            }
        }
    }
}
