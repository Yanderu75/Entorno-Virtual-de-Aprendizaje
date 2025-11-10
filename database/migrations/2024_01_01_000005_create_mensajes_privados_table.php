<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mensajes_privados', function (Blueprint $table) {
            $table->id('id_mensaje');
            $table->foreignId('id_emisor')->constrained('usuarios', 'id_usuario')->onDelete('cascade');
            $table->foreignId('id_receptor')->constrained('usuarios', 'id_usuario')->onDelete('cascade');
            $table->text('mensaje');
            $table->timestamp('fecha_envio')->useCurrent();
            $table->boolean('leido')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensajes_privados');
    }
};
