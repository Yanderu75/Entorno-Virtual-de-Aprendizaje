<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('copias_seguridad', function (Blueprint $table) {
            $table->id('id_backup');
            $table->timestamp('fecha')->useCurrent();
            $table->string('archivo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('copias_seguridad');
    }
};
