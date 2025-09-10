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
        Schema::create('provincias', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_ubigeo', 4)->unique(); // Código UBIGEO de 4 dígitos para provincia
            $table->string('nombre');
            $table->string('departamento_codigo', 2); // FK al código del departamento
            $table->boolean('activo')->default(true);
            $table->timestamps();

            // Índices
            $table->index('codigo_ubigeo');
            $table->index('departamento_codigo');
            $table->index('activo');

            // Foreign key
            $table->foreign('departamento_codigo')->references('codigo_ubigeo')->on('departamentos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provincias');
    }
};
