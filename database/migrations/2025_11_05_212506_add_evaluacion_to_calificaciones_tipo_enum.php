<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar el ENUM para agregar 'evaluacion'
        DB::statement("ALTER TABLE calificaciones MODIFY COLUMN tipo ENUM('tarea', 'examen', 'proyecto', 'evaluacion') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Volver al ENUM original sin 'evaluacion'
        DB::statement("ALTER TABLE calificaciones MODIFY COLUMN tipo ENUM('tarea', 'examen', 'proyecto') NOT NULL");
    }
};
