<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materias', function (Blueprint $table) {
            $table->id('id_materia');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->foreignId('id_docente')->nullable()->constrained('usuarios', 'id_usuario')->onDelete('set null');
            $table->string('periodo', 20)->nullable();
            $table->string('horario', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};
