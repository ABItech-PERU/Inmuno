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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('medico_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('centro_salud_id')->constrained('centros_salud')->onDelete('cascade');
            $table->foreignId('vacuna_id')->nullable()->constrained('vacunas')->onDelete('set null');
            $table->datetime('fecha_hora');
            $table->string('tipo_cita'); // vacunacion, consulta, control, etc.
            $table->string('estado')->default('programada'); // programada, confirmada, completada, cancelada
            $table->text('motivo')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
