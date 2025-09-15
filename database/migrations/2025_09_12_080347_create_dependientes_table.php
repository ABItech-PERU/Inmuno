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
        Schema::create('dependientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('users')->onDelete('cascade'); // Usuario tutor/apoderado
            $table->foreignId('dependiente_user_id')->nullable()->constrained('users')->onDelete('cascade'); // Usuario registrado (si el dependiente se registró después)

            // Datos del dependiente
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('tipo_documento')->default('DNI'); // DNI, CE, etc.
            $table->string('numero_documento')->unique();
            $table->date('fecha_nacimiento');
            $table->enum('genero', ['M', 'F', 'O'])->default('M');
            $table->string('parentesco'); // hijo, hija, apoderado, etc.

            // Datos adicionales
            $table->string('telefono')->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true);

            $table->timestamps();

            // Índices
            $table->index('tutor_id');
            $table->index('dependiente_user_id');
            $table->index('numero_documento');
            $table->index('activo');

            // Evitar duplicados del mismo documento para el mismo tutor
            $table->unique(['tutor_id', 'numero_documento']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dependientes');
    }
};
