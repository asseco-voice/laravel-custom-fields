<?php

use Asseco\BlueprintAudit\App\MigrationMethodPicker;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateCustomFieldPlainTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_field_plain_types', function (Blueprint $table) {
            if (config('asseco-custom-fields.migrations.uuid')) {
                $table->uuid('id')->primary();
            } else {
                $table->id();
            }

            $table->string('name', 150)->unique('cf_name_types');

            MigrationMethodPicker::pick($table, config('asseco-custom-fields.migrations.timestamps'));

            $this->seedData();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_field_plain_types');
    }

    protected function seedData(): void
    {
        $types = array_keys(config('asseco-custom-fields.plain_types'));

        $plainTypes = [];
        foreach ($types as $type) {
            if (config('asseco-custom-fields.migrations.uuid')) {
                $plainTypes[] = [
                    'id'         => Str::uuid(),
                    'name'       => $type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            } else {
                $plainTypes[] = [
                    'name'       => $type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('custom_field_plain_types')->insert($plainTypes);
    }
}
