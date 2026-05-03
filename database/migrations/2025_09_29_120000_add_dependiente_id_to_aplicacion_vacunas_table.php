<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('aplicacion_vacunas', function (Blueprint $table) {
            $table->foreignId('dependiente_id')->nullable()->constrained('dependientes')->onDelete('cascade')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aplicacion_vacunas', function (Blueprint $table) {
            $table->dropForeign(['dependiente_id']);
            $table->dropColumn('dependiente_id');
        });
    }
};
