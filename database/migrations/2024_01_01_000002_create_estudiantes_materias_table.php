<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudiantes_materias', function (Blueprint $table) {
            $table->id('id_estudiante_materia');
            $table->foreignId('id_estudiante')->constrained('usuarios', 'id_usuario')->onDelete('cascade');
            $table->foreignId('id_materia')->constrained('materias', 'id_materia')->onDelete('cascade');
            $table->decimal('promedio', 5, 2)->default(0.00);
            $table->decimal('avance', 5, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudiantes_materias');
    }
};
