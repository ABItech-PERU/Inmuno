<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Use raw statement to avoid requiring doctrine/dbal in environments where it's not installed
        try {
            DB::statement('ALTER TABLE aplicacion_vacunas MODIFY medico_id BIGINT UNSIGNED NULL');
        } catch (\Exception $e) {
            // Fallback using schema builder change (may require doctrine/dbal)
            Schema::table('aplicacion_vacunas', function (Blueprint $table) {
                $table->unsignedBigInteger('medico_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            DB::statement('ALTER TABLE aplicacion_vacunas MODIFY medico_id BIGINT UNSIGNED NOT NULL');
        } catch (\Exception $e) {
            Schema::table('aplicacion_vacunas', function (Blueprint $table) {
                $table->unsignedBigInteger('medico_id')->nullable(false)->change();
            });
        }
    }
};
