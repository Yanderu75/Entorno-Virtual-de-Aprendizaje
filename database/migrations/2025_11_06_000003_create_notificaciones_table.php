<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id('id_notificacion');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario')->onDelete('cascade');
            $table->enum('tipo', ['inscripcion_aprobada', 'inscripcion_rechazada', 'asignacion', 'otro'])->default('otro');
            $table->string('titulo');
            $table->text('mensaje');
            $table->boolean('leido')->default(false);
            $table->timestamp('fecha')->useCurrent();
            $table->timestamps();
            
            $table->index(['id_usuario', 'leido']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};

