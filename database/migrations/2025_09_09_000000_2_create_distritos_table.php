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
        Schema::create('distritos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_ubigeo', 6)->unique(); // Código UBIGEO de 6 dígitos para distrito
            $table->string('nombre');
            $table->string('provincia_codigo', 4); // FK al código de la provincia
            $table->boolean('activo')->default(true);
            $table->timestamps();

            // Índices
            $table->index('codigo_ubigeo');
            $table->index('provincia_codigo');
            $table->index('activo');

            // Foreign key
            $table->foreign('provincia_codigo')->references('codigo_ubigeo')->on('provincias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distritos');
    }
};
