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
        Schema::create('vacunas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('fabricante')->nullable();
            $table->string('tipo'); // ej: virus atenuado, ARNm, etc.
            $table->integer('dosis_requeridas')->default(1);
            $table->integer('intervalo_entre_dosis')->nullable(); // días
            $table->integer('edad_minima')->nullable(); // meses
            $table->integer('edad_maxima')->nullable(); // meses
            $table->text('contraindicaciones')->nullable();
            $table->text('efectos_secundarios')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacunas');
    }
};
