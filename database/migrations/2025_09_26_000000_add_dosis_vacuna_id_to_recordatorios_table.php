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
        Schema::table('recordatorios', function (Blueprint $table) {
            $table->foreignId('dosis_vacuna_id')->nullable()->constrained('dosis_vacunas')->onDelete('set null')->after('vacuna_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recordatorios', function (Blueprint $table) {
            $table->dropConstrainedForeignId('dosis_vacuna_id');
        });
    }
};
