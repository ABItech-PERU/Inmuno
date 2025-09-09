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
        Schema::table('users', function (Blueprint $table) {
            $table->date('fecha_nacimiento')->nullable()->after('email');
            $table->string('telefono')->nullable()->after('fecha_nacimiento');
            $table->text('direccion')->nullable()->after('telefono');
            $table->string('documento_identidad')->nullable()->after('direccion');
            $table->enum('genero', ['masculino', 'femenino', 'otro'])->nullable()->after('documento_identidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'fecha_nacimiento',
                'telefono',
                'direccion',
                'documento_identidad',
                'genero'
            ]);
        });
    }
};
