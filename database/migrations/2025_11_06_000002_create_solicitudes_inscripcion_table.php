<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_inscripcion', function (Blueprint $table) {
            $table->id('id_solicitud');
            $table->foreignId('id_docente')->constrained('usuarios', 'id_usuario')->onDelete('cascade');
            $table->foreignId('id_estudiante')->constrained('usuarios', 'id_usuario')->onDelete('cascade');
            $table->foreignId('id_materia')->constrained('materias', 'id_materia')->onDelete('cascade');
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            $table->text('motivo_rechazo')->nullable();
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->timestamp('fecha_resolucion')->nullable();
            $table->foreignId('id_admin_resolutor')->nullable()->constrained('usuarios', 'id_usuario')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_inscripcion');
    }
};

