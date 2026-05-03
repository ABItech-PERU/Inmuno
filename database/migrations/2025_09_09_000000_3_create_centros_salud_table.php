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
        Schema::create('centros_salud', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // Código único del centro de salud
            $table->string('nombre');
            $table->string('tipo')->default('Centro de Salud'); // Hospital, Centro de Salud, Posta, etc.
            $table->text('direccion');
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('distrito_codigo', 6); // FK al código del distrito
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            $table->integer('capacidad')->default(0); // Capacidad de atención
            $table->text('servicios')->nullable(); // JSON con servicios disponibles
            $table->string('nivel_atencion')->default('I-1'); // I-1, I-2, I-3, I-4, II-1, II-2, II-E, III-1, III-2, III-E
            $table->time('horario_inicio')->default('08:00:00');
            $table->time('horario_fin')->default('18:00:00');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            // Índices
            $table->index('codigo');
            $table->index('tipo');
            $table->index('distrito_codigo');
            $table->index('activo');
            $table->index('nivel_atencion');

            // Foreign key
            $table->foreign('distrito_codigo')->references('codigo_ubigeo')->on('distritos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centros_salud');
    }
};
