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
        Schema::create('aplicacion_vacunas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // paciente
            $table->foreignId('vacuna_id')->constrained('vacunas')->onDelete('cascade');
            $table->foreignId('dosis_vacuna_id')->nullable()->constrained('dosis_vacunas')->onDelete('set null');
            $table->foreignId('medico_id')->constrained('users')->onDelete('cascade'); // médico que aplicó
            $table->foreignId('centro_salud_id')->constrained('centro_saluds')->onDelete('cascade');
            $table->integer('numero_dosis');
            $table->date('fecha_aplicacion');
            $table->string('lote_vacuna')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->text('observaciones')->nullable();
            $table->text('reacciones_adversas')->nullable();
            $table->date('proxima_dosis')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aplicacion_vacunas');
    }
};
