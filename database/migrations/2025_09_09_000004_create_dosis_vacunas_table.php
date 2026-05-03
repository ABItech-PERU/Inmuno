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
        Schema::create('dosis_vacunas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('esquema_vacunacion_id')->constrained('esquema_vacunacions')->onDelete('cascade');
            $table->foreignId('vacuna_id')->constrained('vacunas')->onDelete('cascade');
            $table->integer('numero_dosis'); // 1, 2, 3, etc.
            $table->integer('edad_aplicacion')->nullable(); // en meses
            $table->integer('dias_despues_anterior')->nullable(); // días después de la dosis anterior
            $table->boolean('es_refuerzo')->default(false);
            $table->boolean('es_obligatoria')->default(true)->comment('Si la dosis es obligatoria o opcional');
            $table->boolean('activo')->default(true)->comment('Si la dosis está activa o inactiva');
            $table->text('observaciones')->nullable();
            $table->text('justificacion_medica')->nullable()->comment('Justificación médica de por qué es necesaria esta dosis de vacuna');
            $table->timestamps();

            $table->unique(['esquema_vacunacion_id', 'vacuna_id', 'numero_dosis'], 'dosis_unica_por_esquema');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosis_vacunas');
    }
};
