<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recursos', function (Blueprint $table) {
            $table->id('id_recurso');
            $table->foreignId('id_materia')->constrained('materias', 'id_materia')->onDelete('cascade');
            $table->string('titulo');
            $table->enum('tipo', ['pdf', 'video', 'presentacion']);
            $table->string('ruta');
            $table->timestamp('fecha_subida')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recursos');
    }
};
