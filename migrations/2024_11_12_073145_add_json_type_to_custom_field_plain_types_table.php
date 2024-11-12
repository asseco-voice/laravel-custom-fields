<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $exists = DB::table('custom_field_plain_types')->where('name', 'json')->exists();
        if ($exists) {
            // already exists
            return;
        }

        $types = ['json'];

        $plainTypes = [];
        foreach ($types as $type) {
            if (config('asseco-custom-fields.migrations.uuid')) {
                $plainTypes[] = [
                    'id' => Str::uuid(),
                    'name' => $type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            } else {
                $plainTypes[] = [
                    'name' => $type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('custom_field_plain_types')->insert($plainTypes);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('custom_field_plain_types')
            ->where('name', 'json')
            ->delete();
    }
};
