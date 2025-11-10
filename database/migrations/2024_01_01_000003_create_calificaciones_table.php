<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id('id_calificacion');
            $table->foreignId('id_estudiante_materia')->constrained('estudiantes_materias', 'id_estudiante_materia')->onDelete('cascade');
            $table->enum('tipo', ['tarea', 'examen', 'proyecto']);
            $table->decimal('nota', 5, 2);
            $table->decimal('porcentaje', 5, 2);
            $table->timestamp('fecha')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};
