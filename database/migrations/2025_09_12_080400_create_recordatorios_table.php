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
        Schema::create('recordatorios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // paciente
            $table->foreignId('dependiente_id')->nullable()->constrained('dependientes')->onDelete('cascade'); // para recordatorios de dependientes
            $table->foreignId('vacuna_id')->nullable()->constrained('vacunas')->onDelete('set null');
            $table->foreignId('cita_id')->nullable()->constrained('citas')->onDelete('set null');
            $table->string('tipo'); // vacuna_proxima, cita_programada, refuerzo_pendiente, recordatorio_personal
            $table->string('titulo');
            $table->text('mensaje')->nullable(); // mensaje opcional
            $table->date('fecha_recordatorio');
            $table->time('hora_recordatorio')->nullable();
            $table->string('estado')->default('pendiente'); // pendiente, enviado, leido
            $table->string('metodo_envio')->default('sistema'); // sistema, email, sms
            $table->datetime('enviado_en')->nullable();
            $table->datetime('leido_en')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recordatorios');
    }
};
